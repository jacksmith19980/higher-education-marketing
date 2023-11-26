<?php

namespace App\Http\Controllers\Tenant;

use Response;
use Validator;
use App\Tenant\Models\Date;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Tenant\Models\Field;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Module;
use App\Tenant\Models\Program;
use App\Tenant\Models\Schedule;
use App\Rules\School\UniqueCourse;
use App\Tenant\Models\Application;
use App\Tenant\Models\CustomField;
use App\Tenant\Traits\HasCampuses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\School\CourseHelpers;
use App\Helpers\Permission\PermissionHelpers;
use App\Helpers\customfield\CustomFieldHelper;
use App\Helpers\Application\ApplicationHelpers;

class CourseController extends Controller
{

    use  HasCampuses;

    const PERMISSION_BASE = "course";

    public function __construct()
    {
        $this->middleware('plan.features:application')
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('plan.features:quote_builder')
            ->only(['show']);
    }

    public function index()
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if(!PermissionHelpers::checkActionPermission( self::PERMISSION_BASE, 'view', null))
        {
            return PermissionHelpers::accessDenied();
        }

        $params = [
            'modelName' => Course::getModelName(),
        ];
        $courses = Course::with('campuses')->byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->get();

        return view('back.courses.index', compact('courses', 'params' , 'permissions'));
    }

    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null))
        {
            return PermissionHelpers::accessDenied();
        }

        // if user is not permitted to create courses cross campus
        if(!$permissions['campusesCreate|' . self::PERMISSION_BASE]){
            $campuses = $this->getUserCampusesList();
        }else{
            $campuses = $this->getCampusesList();
        }

        $programs = Program::all()->toArray();
        $programs = Arr::pluck($programs, 'title', 'id');

        $schedules = Schedule::get();

        $customFields = CustomField::where('properties', 'courses')->get();

        return view('back.courses.create', compact('campuses', 'programs', 'schedules', 'customFields'));
    }

    public function store(Request $request)
    {

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }


        $request->validate(
            [
                'title'         => 'required',
                'campuses'      => 'required',
                'properties'    => 'required',
                'slug'          => ['required', new UniqueCourse('slug')],
            ]
        );

        $date_properties = [];
        $course_properties = [];

        //replace the null price with 0
        if (isset($request->properties['date_price']) && $request->properties['date_price']) {
            $tmp = $request->properties;
            foreach ($tmp['date_price'] as $p => $price) {
                if ($price == null) {
                    $tmp['date_price'][$p] = '0';
                }
            }
            $request->properties = $tmp;
        }

        $course_properties = $request->properties;
        $date_properties = $request->properties;

        $course_properties['dates_type'] = $course_properties['dates_type_courses'];

        $date_type = $course_properties['dates_type_courses'];
        if ($date_type) {
            $processedDates = $this->processDates($date_type, $date_properties);
        }

        $course = Course::create(
            [
                'title'      => $request->title,
                'slug'       => $request->slug,
                'properties' => $request->properties,
                'status'     => $request->status,
            ]
        );

        if (isset($request->details)) {
            $course->details = $request->details;
        }
        // Save Custom Fields
        $course = CustomFieldHelper::saveCustomfield($request, $course);

        // Todo upload file, ask directory and name for it
        $course->save();

        if ($course->id !== '' && isset($processedDates)) {
            //foreach($all_dates as $a){
            foreach ($processedDates as $a) {
                $date = Date::create(
                    [
                        'object_id'  => $course->id,
                        'key'        => Str::random(10),
                        'date_type'  => $date_type,
                        'properties' => $a,
                    ]
                );
            }
        }

        $course->campuses()->sync($request->campuses);

        if ($request->program) {
            $course->programs()->sync($request->program);
        }

        $message = ['success' => "Course {$course->title } created successfully!"];

        if (Application::all()->count() > 0) {
            $message['warning'] = 'You need refresh courses lists in your applications to keep updated';
        }

        return redirect(route('courses.index', $course))->with($message);
    }

    public function show(Course $course)
    {

        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|' . self::PERMISSION_BASE]) {
            return redirect(route(PermissionHelpers::REDIRECTIO_ON_FAIL));
        }

        $campuses = Campus::all()->toArray();
        $campuses = Arr::pluck($campuses, 'title', 'id');
        $courseCampuses = $course->campuses->pluck('id', 'title')->toArray();
        $course->load('addons', 'dates');
        return view('back.courses.show', compact('campuses', 'course', 'courseCampuses'));
    }

    public function edit(Course $course)
    {
        if(!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $course))
        {
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


        /* $campuses = Campus::all()->toArray();
        $campuses = Arr::pluck($campuses, 'title', 'id'); */

        $programs = Program::all()->toArray();
        $programs = Arr::pluck($programs, 'title', 'id');

        $courseCampuses = $course->campuses->pluck('id', 'title')->toArray();

        $courseProgramCollection = $course->programs;
        $courseProgram = ($courseProgramCollection->count() > 0) ? $courseProgramCollection->first()->id : null;

        $courseDates = Date::where('object_id', $course->id)->get()->toArray();

        $schedules = Schedule::get();

        $customFields = CustomField::where('properties', 'courses')->get();

        return view(
            'back.courses.edit',
            compact('campuses', 'course', 'courseCampuses', 'programs', 'courseProgram', 'courseDates', 'schedules', 'customFields')
        );
    }

    public function update(Request $request, Course $course)
    {
        if(!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE , 'edit', $course))
        {
            return PermissionHelpers::accessDenied();
        }

        $request->validate([
                'title'      => 'required',
                'campuses'   => 'required',
                'properties' => 'required',
                'slug'       => ['required', new UniqueCourse('slug', $course)],
        ]);

        $date_properties = [];
        $course_properties = [];

        //replace the null price with 0
        if (isset($request->properties['date_price']) && $request->properties['date_price']) {
            $tmp = $request->properties;
            foreach ($tmp['date_price'] as $p => $price) {
                if ($price == null) {
                    $tmp['date_price'][$p] = '0';
                }
            }
            $request->properties = $tmp;
        }

        $course_properties = $request->properties;
        $date_properties = $request->properties;

        $course_properties['dates_type'] = $course_properties['dates_type_courses'];
        $date_type = $course_properties['dates_type_courses'];

        /*unset($course_properties['dates_type'], $course_properties['start_date'], $course_properties['end_date'], $course_properties['date_schudel'], $course_properties['date_price']);*/

        unset($date_properties['weighting'], $date_properties['course_registeration_fee'], $date_properties['course_materials_fee'], $date_properties['featured_image'], $date_properties['detail_link'], $date_properties['dates_type_courses'], $date_properties['video']);

        $dId = [];
        $dId = $request->date_id;

        $courseProperties = Course::where('id', $course->id)->get(['properties'])->toArray();

        foreach ($courseProperties as $k => $v) {
            if (array_key_exists('start_date', $v['properties']) || array_key_exists('date', $v['properties'])) {
                $isKey = true;
            } else {
                $isKey = false;
            }
        }

        $allIds = [];
        $removedIds = [];

        if ($isKey == true) {
            $allDatesIds = Date::where('object_id', $course->id)->get(['id'])->toArray();
            if (! empty($allDatesIds)) {
                foreach ($allDatesIds as $k => $v) {
                    $allIds[] = $v['id'];
                }
                if (empty($dId)) {
                    $removedIds = $allIds;
                } elseif (! empty($dId)) {
                    $removedIds = array_diff($allIds, $dId);
                }
            }
        }

        if (! empty($removedIds)) {
            foreach ($removedIds as $remove) {
                Date::where('id', $remove)->delete();
            }
        }

        $all_dates = [];
        if ($date_type == 'specific-dates' || $date_type == 'all-year') {
            $date_properties['addons'] = [];

            if (isset($date_properties['start_date'])) {

                foreach ($date_properties['start_date'] as $key => $dateProb) {
                    if ($dId) {
                        if (array_key_exists($key, $dId)) {
                            $all_dates[$key]['date_id'] = $dId[$key];
                        }
                    }
                    $all_dates[$key]['start_date'] = $date_properties['start_date'][$key];
                    $all_dates[$key]['end_date'] = $date_properties['end_date'][$key];
                    $all_dates[$key]['date_schudel'] = $date_properties['date_schudel'][$key];
                    $all_dates[$key]['date_price'] = $date_properties['date_price'][$key];
                    $all_dates[$key]['addons'] = [];
                }

            }
        }

        if ($date_type == 'single-day') {
            if (isset($date_properties['date'])) {

                foreach ($date_properties['date'] as $key => $dateProb) {
                    if ($dId) {
                        if (array_key_exists($key, $dId)) {
                            $all_dates[$key]['date_id'] = $dId[$key];
                        }
                    }
                    $all_dates[$key]['date'] = $date_properties['date'][$key];
                    $all_dates[$key]['start_time'] = $date_properties['start_time'][$key];
                    $all_dates[$key]['end_time'] = $date_properties['end_time'][$key];
                    $all_dates[$key]['date_price'] = $date_properties['date_price'][$key];
                }
            }
        }

        $course->update([
                'title'      => $request->title,
                'slug'       => $request->slug,
                'properties' => $course_properties,
                'status'     => $request->status,
        ]);

        $course->campuses()->sync($request->campuses);

        if ($request->program) {
            $course->programs()->sync($request->program);
        }

        //dd($all_dates);
        if (! empty($all_dates)) {
            foreach ($all_dates as $a) {
                if (array_key_exists('date_id', $a)) {
                    $dateId = $a['date_id'];
                    unset($a['date_id']);
                    $date = Date::find($dateId);
                    $date->update(
                        [
                            //'object_id' => $course->id,
                            'date_type'  => $date_type,
                            'properties' => $a,
                        ]
                    );
                } else {
                    Date::create(
                        [
                            'object_id' => $course->id,
                            'key'        => Str::random(10),
                            'date_type'  => $date_type,
                            'properties' => $a,
                        ]
                    );
                }
            }
        }

        if ($request->module_title) {
            foreach ($request->module_title as $title) {
                $module = $course->modules;

                if (! empty($title)) {
                    if (count($module) > 0) {
                        $module[0]->update(['title' => $title]);
                    } else {
                        $module = Module::create(['title' => $title]);
                        $module->course()->associate($course)->save();
                    }
                }
            }
        }

        $course = CustomFieldHelper::saveCustomfield($request, $course);

        $course->save();

        return redirect(route('courses.index'));
    }

    public function destroy(Course $course)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $course)) {
            return PermissionHelpers::accessDenied();
        }
        // Delete Application
        if ($response = $course->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $course->id],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 404,
                    'response' => $response,
                ]
            );
        }
    }


    /**
     * Save Course Properties Price, Dates, Addons
     */
    public function saveCourseProperties(Request $request, Course $course)
    {
        if ($request->has('properties')) {
            // Save Course Dates
            if (isset($request->properties['pricing'])) {
                $props = $course->properties;
                $props['pricing'] = $request->properties['pricing'];

                $course->update(
                    [
                        'properties' => $props,
                    ]
                );

                return redirect(route('courses.show', $course));
            }
        }

        return Response::json(
            [
                'status'   => 500,
                'response' => 'fail',
                'extra'    => ['message' => 'Something went worng'],
            ]
        );
    }

    public function processDates($dateType, $date_properties)
    {
        if ($date_properties) {
            unset($date_properties['weighting'], $date_properties['course_registeration_fee'], $date_properties['course_materials_fee'], $date_properties['featured_image'], $date_properties['detail_link'], $date_properties['dates_type_courses'], $date_properties['video']);
        }

        if($dateType == 'specific-dates'){
            if (isset($date_properties['start_date'])) {
                foreach ($date_properties['start_date'] as $key => $dateProb) {
                    $all_dates[$key]['start_date'] = $date_properties['start_date'][$key];
                    $all_dates[$key]['end_date'] = $date_properties['end_date'][$key];
                    $all_dates[$key]['date_schudel'] = $date_properties['date_schudel'][$key];
                    $all_dates[$key]['date_price'] = $date_properties['date_price'][$key];
                    $all_dates[$key]['addons'] = [];
                }
            }
        }
        if ($dateType == 'single-day') {
            if (isset($date_properties['date'])) {
                foreach ($date_properties['date'] as $key => $dateProb) {
                    $all_dates[$key]['date'] = $date_properties['date'][$key];
                    $all_dates[$key]['start_time'] = $date_properties['start_time'][$key];
                    $all_dates[$key]['end_time'] = $date_properties['end_time'][$key];
                    $all_dates[$key]['date_price'] = $date_properties['date_price'][$key];
                }
            }
        }
        return $all_dates;
    }

    /**
     * Get Selected Template form
     */
    public function getCourseDatesTemplate($payload)
    {
        $key = Str::random(10);
        $html = view(
            'back.courses._partials.dates.dates-template.'.$payload['template'].'.'.$payload['template'],
            compact('key')
        )->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    /**
     * Delete Course Addon
     *
     * @param Course $course
     * @param Request $request
     * @return void
     */
    public function courseAddonDelete(Course $course, Request $request)
    {
        $props = $course->properties;

        $addons = $props['addons']['addons'];
        unset($addons[$request->addon]);
        $props['addons']['addons'] = $addons;
        $course->update(
            [
                'properties' => $props,
            ]
        );

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['key' => $request->addon],
            ]
        );
    }

    /**
     * get course addon form  -- REQUIRED
     */
    public function getDateAddonsTemplate($payload)
    {
        parse_str($payload, $query);
        extract($query);
        $html = view('back.courses._partials.dates.dates-template.specific-dates.addons', compact('key'))->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }



    public function getCoursePricingTemplate($payload)
    {
        $html = view('back.courses._partials.pricing.pricing-template.'.$payload['template'])->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function getSpecificDatesTemplate($payload)
    {
        $html = view('back.courses._partials.dates-template.specific-dates.specific-dates-block')->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function validator($data)
    {
        return Validator::make(
            $data,
            [

                'title' => 'required|string|max:255',

            ]
        );
    }

    /**
     * Add Start And End Date Selection
     */
    public function addSpecificDates($payload)
    {
        $order = $payload['order'];
        $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null))
        {
            return PermissionHelpers::accessDenied();
        }

        if(!$permissions['campusesCreate|' . self::PERMISSION_BASE]){
            $campuses = $this->getUserCampusesList();
        }else{
            $campuses = $this->getCampusesList();
        }
        $campuses[0] = 'All Campuses';
        ksort($campuses);
        $html = view('back.programs._partials.dates-template.start-end-dates' , compact('campuses' , 'order'))->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    /**
     * Add Start And End Date Selection
     */
    public function addAllYearCourses($payload)
    {
        $html = view('back.programs._partials.dates-template.all-year')->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    /**
     * Add Signle Day Date Selection
     */
    public function addSingleDay($payload)
    {
        $html = view('back.programs._partials.dates-template.single-day-dates')->render();

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

    /**
     * Add New Accommodation Option
     */
    public function addAccommodationOption($payload)
    {
        $data = [];
        $html = view('back.quotation._partials.accommodation-row', compact('data'))->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    /**
     * Add New Transfer Option
     */
    public function addTransferOption($payload)
    {
        $data = [];
        $html = view('back.quotation._partials.transfer.transfer-row', compact('data'))->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    /**
     * Add New Misc Option
     */
    public function addMiscOption($payload)
    {
        $data = [];
        $html = view('back.quotation._partials.miscellaneous.miscellaneous-row', compact('data'))->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    /**
     * Add New Misc Option
     */
    public function addAddons($payload)
    {
        $data = [];
        $html = view('back.courses._partials.addons.addon-row', compact('data'))->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    private function getPropiertiesValues(Request $request): array
    {
        $properties = [];
        $propierties_fields = ['featured_image', 'video'];

        foreach ($propierties_fields as $field) {
            if (isset($request->$field)) {
                $properties[$field] = $request->$field;
            }
        }

        return $properties;
    }

    public function information(Course $course)
    {
        return view('front.recruitment_assistant._partials.courses.modal-information', compact('course'));
    }

    public function courseFromCampus(Request $request)
    {
        $courses = [];
        $courses_selected_by_user_on_backend = Field::find($request->element)->data;
        $campus = Campus::find($request->campus);
        foreach ($campus->courses->pluck('title', 'slug') as $key => $value) {
            if (empty($courses_selected_by_user_on_backend)) {
                $courses[] = ['id' => $key, 'text' => $value];
            } elseif (in_array($value, $courses_selected_by_user_on_backend)) {
                $courses[] = ['id' => $key, 'text' => $value];
            }
        }

        return json_encode($courses);
    }

    public function coursesFromProgram($payload)
    {
        $html = '';
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId(Program::findOrFail($payload['program'])->courses);

        if (count($courses) > 1) {
            $html = view('back.shared._partials.field_value', [

                'data'          => $courses,
                'name'          => 'course',
                'required'      => true,
                'label'         => 'Course',
                'attr'          => 'onchange=app.courseInstructors(this)',
                'placeholder'   => 'Select Course',

            ])->render();
        } elseif (count($courses) == 1) {
            $html = view(
                'back.shared._partials.field_value',
                [
                    'data'          => $courses,
                    'name'          => 'course',
                    'required'      => true,
                    'attr'          => 'onchange=app.courseInstructors(this)',
                    'label'         => 'Course',
                    'placeholder'   => 'Select Course',
                ]
            )->render();
        } else {
            $html = '<div class="alert alert-danger>No course assigned to this program</div>';
        }

        return response()->json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    public function startDatesAvailable(Request $request)
    {
        $course_id = $request->course;
        $course = Course::with('dates')->findOrFail($course_id);

        return json_encode(
            count($course->dates()->get()) ? 1 : 0
        );
    }

    public function startDatesAndAddons(Request $request)
    {
        $course_slug = $request->course;

        $course = Course::with('dates', 'addons')->where('slug', $course_slug)->first();

        $field = Field::find($request->element);

        $hash = $request->hash;

        $addonsHtml = '';
        $datesHtml = '';

        if ($course && count($course->dates()->get()) > 0) {
            $datesData = ApplicationHelpers::dateDataHandler($course, $field);

            $datesHtml = view(
                'front.applications.application-layouts.oiart.courses.partials.dates',
                compact('datesData', 'course', 'field', 'hash')
            )->render();
        }

        $customFieldsHtml = '';
        if (isset($course->properties['customfields']) && isset($field->properties['customFields'])) {
            //$course->properties['customfields']
            $customFields = CustomField::whereIN('slug', array_keys($course->properties['customfields']))->get()->keyBy('slug')->toArray();
            $customFieldsHtml = view(
                'front.applications.application-layouts.oiart.courses.partials.custom-fields',
                compact('customFields', 'course', 'field', 'hash')
            )->render();
        }

        if ( isset($course) && count($course->addons()->get()) > 0) {
            $addonsData = [];
            foreach ($course->addons()->get() as $addon) {
                $addonsData[] = [
                    'id'        => $addon->id,
                    'object_id' => $addon->object_id,
                    'key'       => $addon->key,
                    'title'     => $addon->title,
                    'price'     => $addon->price,
                ];
            }

            $addonsHtml = view(
                'front.applications.application-layouts.oiart.courses.partials.addons',
                compact('addonsData', 'course', 'field', 'hash')
            )->render();
        }
        return Response::json([
            'status'   => 200,
            'response' => 'success',
            'extra'    => [
                'datesHtml' => $datesHtml,
                'addonsHtml' => $addonsHtml,
                'customFieldsHtml' => $customFieldsHtml,
            ],
        ]);
    }

    public function newForm(Request $request)
    {
        $field = Field::findOrFail($request->element);

        return view('front.applications.application-layouts.oiart.courses.courses', compact('field'))->render();
    }

    public function getCourseDatesSelection($payload)
    {
        $html = view('back.courses._partials.dates-template.'.$payload['dateType'])->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }
}
