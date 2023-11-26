<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Plan;
use App\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Role;
use App\Events\Tenant\TenantDeleted;
use App\Events\Tenant\TenantWasCreated;
use App\Events\Tenant\UsersWereInvited;
use App\Repositories\Interfaces\SchoolRepositoryInterface;
use App\Repositories\UserRepository;

class SchoolController extends Controller
{
    protected $schoolRepository;

    /** @var UserRepository */
    private $userRepository;
    private $settingSessionName;

    public function __construct(
        SchoolRepositoryInterface $schoolRepository,
        UserRepository $userRepository
    )
    {
        $this->settingSessionName = 'settings-'.session('tenant');
        session()->forget('tenant');
        session()->forget($this->settingSessionName);
        $this->schoolRepository = $schoolRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Load All Schools View
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        session()->forget('tenant');
        session()->forget($this->settingSessionName);
        $params = [
            'modelName'   => School::getModelName(),
        ];
        $schools = $request->user()->schools;

        return view('back.schools.index', compact('schools', 'params'));
    }

    /**
     * Render Create New School View
     * @return type
     */
    public function create(Request $request)
    {
        session()->forget('tenant');
        session()->forget($this->settingSessionName);
        $roles = [1 => 'Super Admin'];
        $plan = null;
        if ($request->plan) {
            $plan = Plan::findOrFail($request->plan);
        }
        return view('back.schools.create', compact('plan', 'roles'));
    }

    /**
     * Store New School
     * @param Request $request
     * @return View
     */
    public function store(Request $request)
    {

        session()->forget('tenant');
        session()->forget($this->settingSessionName);
        // Validate Request
        $request->validate(
            [
                'name'  => 'required|max:255',
            ]
        );

        $logo = '';
        $user = $request->user();

        // Upload file
        if ($request->hasFile('file')) {
            $logo = $request->file->store('images');
        }

        $school = $this->schoolRepository->create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'logo'        => $logo,
            'description' => $request->description,
        ]);

        $user->schools()->save($school);

        // dispatch Event
        event(new TenantWasCreated($school));

        session()->put('tenant', $school->uuid);

        // create Schools Super Admin Role
        $role = Role::create([
            'name'          => 'Super Admin',
            'school_id'     => $school->id,
            'full_access'   => true,
        ]);
        // Assign the role to the current user
        $user->assignRole($role);

        if (count(array_filter($request->users_emails))) {
            event(new UsersWereInvited($school, $request->users_emails, $request->users_names, $request->users_roles , true));
        }

        if (isset($request->settings)) {
            foreach ($request->settings as $key => $setting_item) {
                foreach ($setting_item as $slug => $value) {
                    $setting = Setting::firstOrNew(['slug' => $slug]);
                    $setting->group = $key;
                    if ($value) {
                        $setting->data = (is_array($value)) ? array_filter($value) : $value;
                    } else {
                        $setting->data = '';
                    }
                    $setting->save();
                }
            }
        }
        return redirect()->route('tenant.switch', $school);
    }

    /**
     * Show A Specific School
     * @param School $school
     * @return type
     */
    public function show(School $school)
    {
        dd($school);
    }

    /**
    * Render Edit School View
    * @return type
    */
    public function edit(School $school, Request $request)
    {
        session()->forget('tenant');
        session()->forget($this->settingSessionName);
        return view('back.schools.edit', compact('school'));
    }

    public function destroy(Request $request, $school)
    {
        if ( !in_array(Auth::guard('web')->user()->id, explode("," , env('SUPER_ADMIN_ID')) )) {
            abort(419);
        }
        $school = School::where('slug', $school)->first();
        // Delete School
        event(new TenantDeleted($school));

        return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $school->id],
            ]);
    }
}
