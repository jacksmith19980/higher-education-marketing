<?php

namespace App\Http\Controllers\Tenant;

use Storage;
use Response;
use App\School;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Admission;
use Spatie\Permission\Models\Role;
use App\Tenant\Traits\Integratable;
use App\Http\Controllers\Controller;
use App\Helpers\School\PluginsHelper;
use App\Helpers\School\SettingsHelper;
use App\Events\Tenant\UsersWereInvited;
use App\Repositories\SettingRepository;
use App\Helpers\Permission\PermissionHelpers;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class SettingController extends Controller
{
    use Integratable;
    const PERMISSION_BASE = "settings";

    protected $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->middleware('plan.features:application');
        $this->settingRepository = $settingRepository;
    }

    public function index()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|'.self::PERMISSION_BASE]) {
            return PermissionHelpers::accessDenied();
        }

        $settings = Setting::byGroup();
        $params = [
            'modelName'   => Setting::getModelName(),
        ];

        $stages = [];
        if ($inetgration = $this->inetgration()) {
            $stagesList = $inetgration->getStages();
            if (isset($stagesList['stages'])) {
                foreach ($stagesList['stages'] as $stage) {
                    $stages[$stage['id']] = $stage['name'];
                }
            }
        }
        $crm = PluginsHelper::getPluginsList('crm');

        $school = School::byUuid(session('tenant'))->first();
        $roles = Arr::pluck(Role::where('school_id', $school->id)->orWhere('id', '=', 2)->get(), 'name', 'id');

        $users = $school->users;
        $admissions = Admission::get();
        $schedules = Schedule::get();
        $pluginsHelper = new PluginsHelper();

        return view('back.settings.index', compact(
            'settings',
            'params',
            'users',
            'admissions',
            'stages',
            'pluginsHelper',
            'school',
            'schedules',
            'roles',
            'crm',
            'permissions'
        ));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE
        ]);
        if (! $permissions['edit|' . self::PERMISSION_BASE]) {
            return redirect(route(PermissionHelpers::REDIRECTIO_ON_FAIL));
        }
        // upload files
        $uploaded = [];
        $up = [];
        if ($request->action !== 'add_branding') {
            foreach ($request->files as $name => $file) {
                $uploaded[$name]['path'] = Storage::putFile('/'.session('tenant'), $request->file($name));
                $uploaded[$name]['name'] = $request->file($name)->getClientOriginalName();
            }
            $data = $request->except('_token', 'file', 'group');
            if (! empty($uploaded)) {
                $data = $uploaded + $data;
            }
        }
        if ($request->has('action') && $request->action == 'add_branding') {
            $langs = $request->input('logo_locale');
            if (! empty($langs)) {
                $count = count($langs);

                for ($i = 0; $i < $count; $i++) {
                    if (!$langs[$i]) {
                        continue;
                    }
                    //$up[$i] =  explode(',' , $request->input('logo_locale')[$i]);
                    if (! empty($request->file('logos'))) {
                        foreach ($request->file('logos') as $name => $file) {
                            $files_uploaded[$name]['path'] = Storage::putFile('/'.session('tenant'), $file);
                            $files_uploaded[$name]['name'] = $file->getClientOriginalName();
                        }
                    }

                    if (empty($files_uploaded[$i])) {
                        $files_uploaded[$i]['path'] = $request->input('path')[$i];
                        $files_uploaded[$i]['name'] = $request->input('name')[$i];
                        $up['logos'][$langs[$i]] = $files_uploaded[$i];
                    } else {
                        $up['logos'][$langs[$i]] = $files_uploaded[$i];
                    }
                }
            }

            foreach ($request->files as $name => $file) {
                if ($name == 'logo' || $name == 'icon') {
                    $uploaded[$name]['path'] = Storage::putFile('/'.session('tenant'), $request->file($name));
                    $uploaded[$name]['name'] = $request->file($name)->getClientOriginalName();
                }
            }

            $data = $request->except('_token', 'file', 'group', 'logo_locale', 'logos', 'path', 'name');
            if (! empty($up)) {
                $data = $up + $data;
            }

            if (! empty($uploaded)) {
                $data = $uploaded + $data;
            }
        }

        $permissions =  PermissionHelpers::areGranted([
            'create|user'
        ]);
        // Invite Users
        if (
            $request->has('action')
            && $request->action === 'invite_users'
            && $permissions['create|user']
        ) {
            $this->inviteUsers($data);
            if (isset($request->hash)) {
                if ($request->hash == 'users') {
                    return redirect(route('settings.index').'#'.'invite');
                }
            }

            return redirect(route('settings.index'));
        }
        if (count($data)) {
            foreach ($data as $slug => $value) {
                $setting = Setting::firstOrNew(['slug' => $slug]);
                $setting->group = $request->group;
                if ($value) {
                    $setting->data = (is_array($value)) ? array_filter($value) : $value;
                } else {
                    $setting->data = '';
                }
                $setting->save();
            }
        } else {
            Setting::where(['group' => $request->group])->delete();
        }
        // Update School Settings session
        $settings = SettingsHelper::updateSchoolSettings();
        $hash = '';
        if (isset($request->hash)) {
            switch ($request->hash) {
                case 'itour':
                    $hash = 'branding';
                    break;
                case 'branding':
                    $hash = 'login';
                    break;
                case 'login':
                    $hash = 'users';
                    break;
                default:
                    $hash = '';
                    break;
            }
        }

        return redirect(route('settings.index').'#'.$hash);
    }

    /**
     * Invite Users to School
     *
     * @param [array] $data
     * @return void
     */
    protected function inviteUsers($data)
    {
        if (
            isset($data['users_emails'], $data['users_names'], $data['users_roles']) &&
            (count($data['users_emails']) > 0 && $data['users_emails'][0] !== null) &&
            (count($data['users_names']) > 0 && $data['users_names'][0] !== null) &&
            (count($data['users_roles']) > 0 && $data['users_roles'][0] !== null)
        ) {
            $school = School::byUuid(session('tenant'))->first();
            event(new UsersWereInvited($school, $data['users_emails'], $data['users_names'], $data['users_roles']));
        }
    }

    protected function uploadFiles($files)
    {
        dd($files);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function toggle($payload)
    {

        // Get the Setting Switcher
        if ($settings = Setting::where('slug', $payload['item'])->first()) {
            if ($payload['status'] == 'enable') {
                $settings->data = true;
            } else {
                $settings->data = null;
            }

            if ($settings->save()) {
                return Response::json([
                        'status'    => 200,
                        'response'  => 'success',
                        'extra'     => ['html' => '', 'message' => 'Updated successfully'],
                ]);
            }
        }
    }

    public function addScheduleOutsideSettings(Request $request)
    {
        $request->validate([
            'schedule_label'        => 'required',
            'schedule_start_time'   => 'required',
            'schedule_end_time'     => 'required',
        ]);
        // return $request;
        $data = [
            'schedule_label' => $request->schedule_label,
            'schedule_start_time' => $request->schedule_start_time,
            'schedule_end_time'  => $request->schedule_end_time,
        ];
        $sch = Schedule::create([
            'label'         => $request->schedule_label,
            'start_time'    => $request->schedule_start_time,
            'end_time'       => $request->schedule_end_time,
        ]);

        $sch->save();
        $id = $sch->id;
        if ($sch) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html' => '', 'message' => 'Updated successfully', 'res' => $data, 'id' => $id],
            ]);
        }
    }

    public function addNewSchedule(Request $request)
    {
        foreach ($request->schedule_label as $k => $v) {
            if ($request->schedule_id[$k] == '') {
                $sch = Schedule::create([
                    'label'         => $v,
                    'start_time'    => $request->schedule_start_time[$k],
                    'end_time'       => $request->schedule_end_time[$k],
                ]);
            } elseif ($request->schedule_id[$k] !== null) {
                $sch = Schedule::where('id', $request->schedule_id[$k])->update([
                    'label'         => $v,
                    'start_time'    => $request->schedule_start_time[$k],
                    'end_time'       => $request->schedule_end_time[$k],
                ]);
            }
        }

        $hash = '';
        if (isset($request->hash)) {
            switch ($request->hash) {
                case 'itour':
                    $hash = 'branding';
                    break;
                case 'branding':
                    $hash = 'login';
                    break;
                case 'login':
                    $hash = 'users';
                    break;
                default:
                    $hash = '';
                    break;
            }
        }

        return redirect(route('settings.index').'#'.$hash);
    }

    public function deleteSchedule($id)
    {
        $res = Schedule::where('id', $id)->delete();

        if ($res == true) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                 'extra'     => ['html' => '', 'message' => 'Deleted successfully...'],
            ]);
        } else {
            return Response::json([
                'status'    => 200,
                'response'  => 'failed',
            ]);
        }
    }

    public function addDegree($request)
    {
        $html = view('back.settings.education._partials.degree-row' , ['disabled' => false])->render();
        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function storeDegrees()
    {
        $list = [];
        if (request()->has('value')) {
            foreach (request()->value as $key => $value) {
                $list[$value] = request()->label[$key];
            }
        }
        $degrees = Setting::updateOrCreate(
            ['slug' => 'degrees'],
            [
                'group' => 'education',
                'data' => $list
            ]
        );
        session()->put('settings-'.session('tenant'), Setting::byGroup());

        return redirect(route('settings.index'))->withSuccess('Saved Successfully!');
    }


    public function getAuthLayout($payload)
    {
        $layout = !(isset($payload['layout'])) ? 'basic' : strtolower($payload['layout']);
        $html = view('back.settings.auth.' . $layout,
                [
                    'disabled' => (isset($payload['diasabled']) && $payload['diasabled']) ? true: false,
                ])->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }
    public function addHsCustomProperty($payload)
    {
        $html = view('back.settings._partials.integrations.hubspot-property-row' , [
            'order' => $payload['order'],
        ])->render();


        return Response::json(
        [
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                    'html' => $html
            ],
        ]
        );
    }
}
