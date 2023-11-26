<?php

namespace App\Http\Controllers\Tenant;

use Storage;
use Response;
use App\School;
use Illuminate\Support\Arr;
use App\Tenant\Models\Field;
use Illuminate\Http\Request;

use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Application;
use App\Tenant\Models\CustomField;
use App\Tenant\Traits\HasCampuses;
use App\Rules\School\UniqueProgram;
use App\Http\Controllers\Controller;
use App\Helpers\Assistant\VideoHelpers;
use App\Tenant\Traits\ExtractProperties;
use App\Helpers\Quotation\QuotationHelpers;
use App\Http\Requests\Tenant\ProgramRequest;
use App\Helpers\Permission\PermissionHelpers;
use App\Helpers\customfield\CustomFieldHelper;
use App\Helpers\Application\ApplicationHelpers;

class ProgramController extends Controller
{
    use ExtractProperties;
    use  HasCampuses;


    const  PERMISSION_BASE = "program";

    public function __construct()
    {
        $this->middleware('plan.features:application')
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);


        if(!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null))
        {
            return PermissionHelpers::accessDenied();
        }


        $params = [
            'modelName'   => Program::getModelName(),
        ];

        $programs = Program::with('campuses', 'courses')->byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->get();
        $schedules = Schedule::get();

        return view('back.programs.index', compact('programs', 'params', 'schedules' , 'permissions'));
    }

    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }


        // if user is not permitted to create courses cross campus
        if(!$permissions['campusesCreate|' . self::PERMISSION_BASE]){
            $campuses = $this->getUserCampusesList();
        }else{
            $campuses = $this->getCampusesList();
        }

        $courses = Course::doesntHave('programs')->get()->toArray();
        $courses = Arr::pluck($courses, 'title', 'id');

        $schedules = Schedule::get();

        $customFields = CustomField::where('properties', 'programs')->get();

        return view('back.programs.create', compact(
            'campuses',
            'courses',
            'schedules',
            'customFields'
        ));
    }

    public function store(ProgramRequest $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }


        $program = new Program();
        $program->title = $request->title;
        $program->slug = $request->slug;
        $program->uid = rand(1000, 9999);
        $program->program_type = $request->program_type;
        $program->properties = $request->properties;

        $uploaded = [];
        if (count($request->files) > 0) {
            $uploaded['path'] = Storage::putFile('/'.session('tenant'), $request->file('featured_image'));
            $uploaded['name'] = $request->file('featured_image')->getClientOriginalName();
        }

        if (isset($request->details)) {
            $program->details = $request->details;
        }

        if (count($uploaded) > 0) {
            $properties = $request->properties;
            $properties['featured_image'] = $uploaded;
            $program->properties = $properties;
        }

        $program = CustomFieldHelper::saveCustomfield($request, $program);

        $program->save();

        $program->campuses()->sync($request->campuses);

        if ($request->courses) {
            $program->courses()->sync($request->courses);
        }

        $message = ['success' => "Campus {$program->title } created successfully!"];

        if (Application::all()->count() > 0) {
            $message['warning'] = 'You need refresh programs lists in your applications to keep updated';
        }

        return redirect(route('programs.index'))->with($message);
    }

    public function show($id)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|' . self::PERMISSION_BASE]) {
            return redirect(route(PermissionHelpers::REDIRECTIO_ON_FAIL));
        }

    }

    public function edit(Program $program)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $program)) {
            return PermissionHelpers::accessDenied();
        }
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
            'campusesEdit|' . self::PERMISSION_BASE
        ]);


        // if user is not permitted to create courses cross campus
        if (!$permissions['campusesEdit|' . self::PERMISSION_BASE]) {
            $campuses = $this->getUserCampusesList();
        } else {
            $campuses = $this->getCampusesList();
        }

        $courses_doesnt_have = Course::doesntHave('programs')->get();
        $courses = $courses_doesnt_have->merge($program->courses)->toArray();
        $courses = Arr::pluck($courses, 'title', 'id');

        $program->load('campuses', 'courses');
        $schedules = Schedule::get();
        $schedules = $schedules->count() ? $schedules : [];
        $customFields = CustomField::where('properties', 'programs')->get();

        return view('back.programs.edit', compact('campuses', 'courses', 'program', 'schedules', 'customFields'));
    }

    public function update(Request $request, Program $program)
    {

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $program)) {
            return PermissionHelpers::accessDenied();
        }

        $request->validate([
            'title'        => 'required',
            'program_type' => 'required',
            'campuses'     => 'required',
            'properties'   => 'required',
            'slug' => ['required', new Uniqueprogram('slug', $program), 'regex:/^[a-zA-Z0-9-_, ]+$/u'],
        ]);
        $program_properties = $program->properties;
        $program->update($request->except('_token', '_method'));

        if (count($request->files) > 0) {
            $uploaded = [];
            $uploaded['path'] = Storage::putFile('/'.session('tenant'), $request->file('featured_image'));
            $uploaded['name'] = $request->file('featured_image')->getClientOriginalName();

            $properties = $request->properties;
            $properties['featured_image'] = $uploaded;
            $program->properties = $properties;
        } else {
            $properties = $program->properties;
            $properties['featured_image'] = array_key_exists('featured_image', $program_properties) ? $program_properties['featured_image'] : '';
            $program->properties = $properties;
        }

        $program = CustomFieldHelper::saveCustomfield($request, $program);

        $program->save();

        $program->campuses()->sync($request->campuses);
        $program->courses()->sync($request->courses);

        return redirect(route('programs.index'));
    }

    public function destroy(Program $program)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $program)) {
            return PermissionHelpers::accessDenied();
        }

        if ($response = $program->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $program->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function getProgramDatesSelection($payload)
    {
        if (! $payload['dateType']) {
            return Response::json([
                'status' => 400,
                'response' => 'failed',
                'extra' => 'dateType is mandatory',
            ]);
        }

        $html = view('back.programs._partials.dates-template.'.$payload['dateType'])->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    public function addSpecificIntakes($payload)
    {
        $order = $payload['order'];
        $html = view('back.programs._partials.dates-template.intake-dates', compact('order'))->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    public function information(School $school, Program $program, Request $request)
    {
        $assistantBuilder = $request->assistantBuilder;
        $step = $request->step;

        $videoUrl = '';
        if (isset($program->properties['video'])) {
            $videoUrl = VideoHelpers::getVideoUrl($program->properties['video']);
        }

        //$$program->properties['date_schudel'];
        $def_schedule = Schedule::get();
        $schedule = [];
        if (! empty($def_schedule)) {
            foreach ($def_schedule as $key => $v) {
                $schedule[$v->id] = QuotationHelpers::amOrPm($v->start_time).' - '.QuotationHelpers::amOrPm($v->end_time);
            }
        }

        /*$def_schedule = Setting::where('slug', 'schedule_label')->first()->data;
        $def_start_time = Setting::where('slug', 'schedule_start_time')->first()->data;
        $def_end_time = Setting::where('slug', 'schedule_end_time')->first()->data;

        $schedule = [];
        if(!empty($def_schedule)){
            foreach($def_schedule as $key => $value){
                $schedule[$value] = $def_start_time[$key] ." - ". $def_end_time[$key];
            }
        }*/

        return view(
            'front.recruitment_assistant._partials.programs.modal-information',
            compact('program', 'school', 'assistantBuilder', 'step', 'schedule', 'videoUrl')
        );
    }

    protected function getChildren($items)
    {
        $children = [];
        foreach ($items as $item) {
            $children[] = [
                'id'    => $item['slug'],
                'text'  => $item['title'],
            ];
        }
        return $children;
    }
    public function filterProgram(Request $request)
    {
        $list = [];
        $programs = null;
        if($request->filled('campus'))
        {
            $programs = Program::whereHas('campuses', function ($q) use ($request) {
                $q->where('campuses.id' , $request->campus)
                ->orWhere('campuses.slug' , $request->campus);
            });
        }

        if($request->filled("programType")){
            if($programs){
                $programs = $programs->where('program_type', $request->programType);
            }else{
                $programs = Program::where('program_type', $request->programType);
            }
        }
        if($programs){
            $programs = $programs->pluck('title', 'slug')->toArray();
            foreach($programs as $slug=> $title){
                $list[] = [
                    'id'    => $slug,
                    'text'  => $title,
                ];
            }
        }
        return json_encode($list);
    }

    public function startDatesAndAddons(Request $request)
    {

        $program_slug = $request->program;
        $program = Program::where('slug', $program_slug)->first();
        $field = Field::find($request->element);
        $datesHtml = '';
        $addonsHtml = '';

        // Return Date HTML if Hide start Date is not selected
        if(!isset($field->properties['hideStartDate']) || !$field->properties['hideStartDate']){

            if (!is_null($program->properties['dates_type'])) {
                switch ($program->properties['dates_type']) {
                    case 'specific-dates':
                        $datesHtml = $this->showSpecificDates($program, $field , $request->campus);
                        break;
                        case 'specific-intakes':
                            $datesHtml = $this->showSpecificIntakesDates($program, $field);
                            break;
                        }
                    }
        }else{
        // Return Hidden start date field
            $datesHtml = $this->showHiddenStartDates($program, $field);;
        }
        $customFieldsHtml = $this->showCustomFields($program, $field);
        $addonsHtml = $this->showAddons($program, $field);
        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => [
                    'datesHtml'         => $datesHtml,
                    'addonsHtml'        => $addonsHtml,
                    'customFieldsHtml'  => $customFieldsHtml,
                ],
            ]
        );
    }

    private function showCustomFields($program, $field)
    {
        if (isset($field->properties['customFields']) && isset($program->properties['customfields']) ) {
            $customFields = CustomField::whereIN('slug', array_keys($program->properties['customfields']))->get()->keyBy('slug')->toArray();
            return $customFieldsHtml = view(
                'front.applications.application-layouts.oiart.programs.partials.custom-fields',
                compact('customFields', 'program', 'field')
            )->render();
        }
        return '';
    }

    private function showSpecificDates($program, $field , $campus  = null)
    {
        $datesData = [];
        $format_date = 'l jS \\of F Y';
        foreach($program->properties['start_date'] as $i=>$date) {

             if (strtotime($program->properties['start_date'][$i]) >= strtotime(date('Y-m-d'))) {
                    if(isset($program->properties['date_schudel'])){
                        $schedule = Schedule::where('id', $program->properties['date_schudel'][$i])->first()->toArray();

                    }else{

                        $schedule = [
                            'label' => '',
                            'start_time' => '',
                            'end_time' => '',
                        ];
                    }
                    $datesData[] = [
                        'start_date'    => $program->properties['start_date'][$i],
                        'end_date'      => $program->properties['end_date'][$i],
                        'date_price'    => $program->properties['date_price'][$i],
                        'date_schudel' =>  $schedule,
                        'date_campus'  => isset($program->properties['date_campus'][$i]) ? $program->properties['date_campus'][$i] : 0,
                    ];
                }
        }

        return view(
            'front.applications.application-layouts.oiart.programs.partials.specific_dates',
            compact('datesData', 'program', 'field', 'format_date' , 'campus')
        )->render();
    }

    private function showSpecificIntakesDates($program, $field)
    {
        $datesData = $program->properties['intake_date'];
        $format_date = 'Y-m-d';

        return view(
            'front.applications.application-layouts.oiart.programs.partials.intake_dates',
            compact('datesData', 'program', 'field', 'format_date', 'locale')
        )->render();
    }

    private function showHiddenStartDates($program, $field)
    {
        return view(
            'front.applications.application-layouts.oiart.programs.partials.hidden_dates',
            compact('program', 'field')
        )->render();
    }

    private function showAddons($program, $field)
    {
        $addonsData = [];
        if (array_key_exists('addons', $program->properties)) {
            for ($i = 0; $i < count($program->properties['addons']['addon_options']); $i++) {
                $addonsData[] = [
                    'addon_options_category' => $program->properties['addons']['addon_options_category'][$i],
                    'addon_options' => $program->properties['addons']['addon_options'][$i],
                    'addon_options_price' => $program->properties['addons']['addon_options_price'][$i],
                ];
            }

            return view(
                'front.applications.application-layouts.oiart.programs.partials.addons',
                compact('addonsData', 'program', 'field')
            )->render();
        } else {
            return '';
        }
    }

    public function getProgramPricingTemplate($payload)
    {
        $html = view('back.programs._partials.pricing-template.'.$payload['template'])->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    /**
     * Get Calendar Schedule Template
     */
    public function getCalendarSchedule()
    {
        return view('back.courses._partials.schedule.schedule')->render();
    }
}
