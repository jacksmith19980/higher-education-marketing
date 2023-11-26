<?php

namespace App\Http\Controllers\Tenant;

use Session;
use Response;
use Carbon\Carbon;
use App\Tenant\Models\Group;
use Illuminate\Http\Request;
use App\Tenant\Models\Course;
use App\Tenant\Models\Lesson;
use App\Exports\LessonsExport;
use App\Tenant\Models\Program;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Semester;
use Illuminate\Validation\Rule;
use App\Tenant\Models\Classroom;
use App\Helpers\Date\DateHelpers;
use App\Tenant\Models\Attendance;
use App\Tenant\Models\Instructor;
use App\Exports\AttendancesExport;
use App\Tenant\Models\Lessoneable;
use Illuminate\Support\Facades\DB;
use App\Helpers\School\GroupHelpers;
use App\Helpers\School\ModelHelpers;
use App\Http\Controllers\Controller;
use App\Tenant\Models\ClassroomSlot;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\SemesterHelpers;
use App\Helpers\School\ClassroomHelpers;
use App\Helpers\School\InstructorHelpers;
use App\Helpers\Application\ProgramHelpers;
use App\Helpers\Quotation\QuotationHelpers;
use App\Helpers\School\ClassRoomSlotHelpers;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Tenant\LessoneableController;
use App\Repositories\Interfaces\InstructorRepositoryInterface;

class LessonController extends Controller
{
    protected $instructor_repository;

    protected $bulkEditProps = [
        'course_id'         => 'course',
        'program_id'        => 'program',
        'group_id'          => 'group',
        'module_id'         => 'module',
        'instructor_id'     => 'instructor',
        'classroom_id'      => 'classroom',
        'classroom_slot_id' => 'classroom_slot',
        'module_id'         => 'module',
    ];

    public function __construct(
        InstructorRepositoryInterface $instructor_repository
    ) {
        $this->middleware('plan.features:sis')
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        $this->instructor_repository = $instructor_repository;
    }

    public function index()
    {
        $params = [
            'modelName' => Lesson::getModelName(),
        ];
        $schedule = Schedule::get();

        $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
        $instructors = ModelHelpers::convertFirstNameLastnameInNameAssocWithId($this->instructor_repository->all());

        switch (config('app.locale')) {
            case 'en':
                $datatablei18n = 'English';
                break;
            case 'es':
                $datatablei18n = 'Spanish';
                break;
            case 'fr':
                $datatablei18n = 'French';
                break;
            default:
                $datatablei18n = 'English';
        }

        return view('back.lessons.index',
            compact('datatablei18n', 'instructors', 'classrooms', 'courses')
        );
    }

    public function getLessons(Request $request)
    {
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = $request->columns[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value

        //# Custom Field value
        $start_date     = $request->start_date;
        $end_date       = $request->end_date;
        $instructors    = $request->instructors;
        $classrooms     = $request->classrooms;
        $courses        = $request->courses;
        //$student_statuses   = $request->student_statuses;

        //# Total number of records without filtering
        $totalRecords = Lesson::select('count(*) as allcount')
            ->join('instructors', 'lessons.instructor_id', '=', 'instructors.id')
            ->count();

        //# Fetch records
        $lessons = Lesson::with('lessoneable', 'course', 'attendances', 'classroomSlot', 'instructor', 'classroom')
            ->join('instructors', 'lessons.instructor_id', '=', 'instructors.id')
            ->select([
            'lessons.*',
            'lessons.id AS id',
            'lessons.id AS lesson_id',
            'date',
            'course_id',
            'classroom_slot_id',
            'instructor_id',
            'classroom_id',
            'instructors.id as instructor_id',
            'instructors.first_name',
            'instructors.last_name',
            'instructors.email',
        ])
        ->skip($row)
        ->take($rowperpage);

        $totalRecordWithFilter = Lesson::select('count(*) as allcount')
            ->join('instructors', 'lessons.instructor_id', '=', 'instructors.id');

        //# Search
        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';

            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('lessons.date', [$start_date, $end_date]);
            };
            $totalRecordWithFilter->where($searchDatesRange);
            $lessons->where($searchDatesRange);
        }

        if (isset($instructors) && !empty($instructors)  && is_array($instructors)) {
            $searchInstructors = function ($query) use ($instructors) {
                $query->whereIn('lessons.instructor_id', array_map('intval', array_values($instructors)));
            };
            $totalRecordWithFilter->where($searchInstructors);
            $lessons->where($searchInstructors);
        }

        if (isset($classrooms) && !empty($classrooms)  && is_array($classrooms)) {
            $searchClassroom = function ($query) use ($classrooms) {
                $query->whereIn('lessons.classroom_id', array_map('intval', array_values($classrooms)));
            };
            $totalRecordWithFilter->where($searchClassroom);
            $lessons->where($searchClassroom);
        }

        if (isset($courses) && !empty($courses)  && is_array($courses)) {
            $searchCourses = function ($query) use ($courses) {
                $query->whereIn('lessons.course_id', array_map('intval', array_values($courses)));
            };
            $totalRecordWithFilter->where($searchCourses);
            $lessons->where($searchCourses);
        }

        if ($searchValue != '') {
            $searchQuery = function ($query) use ($searchValue) {
                $query->whereHas('instructor', function ($query) use ($searchValue) {
                    return $query->where('first_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('last_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('email', 'like', '%'.$searchValue.'%');
                });
            };

            //# Total number of record with filtering
            $totalRecordWithFilter->where($searchQuery);

            $lessons->where($searchQuery);
        }

        $lessons->orderBy($columnName, $columnSortOrder);

        $totalRecordWithFilter = $totalRecordWithFilter->count();
        $lessons = $lessons->get();
        $data = [];

        $start_time = '';
        $end_time = '';
        $schedule = Schedule::get();


        foreach ($lessons as $lesson) {
            foreach ($schedule as $s) {
                if ($s->id == $lesson->classroomSlot->schedule_id) {
                    $end_time = QuotationHelpers::amOrPm($s->end_time);
                    $start_time = QuotationHelpers::amOrPm($s->start_time);
                }
            }
            $select= view('back.lessons._partials.select-row' , ['item' => $lesson])->render();

            $groups = $lesson->groups()->withCount('students')->get();
            $cohorts = $groups->pluck('title')->toArray();
            $students_count = $groups->pluck('students_count')->toArray();

            $cohorts = "<p title='". implode(", " , $cohorts) ."'>"  . count($cohorts) .  "</p>";

            $action = view('back.lessons._partials.lesson-action' , ['lesson' => $lesson])->render();



            $data[] = [
                'select'        => $select,
                'title'         => 'Lesson',
                'instructor'    => isset($lesson->instructor) ? $lesson->instructor->name : '',
                'classroom'     => isset($lesson->classroom->title) ? $lesson->classroom->title : '',
                'course'        => isset($lesson->course->title) ? $lesson->course->title : '',
                'cohorts'       => $cohorts,
                'students'      => array_sum($students_count),
                'date'          => isset($lesson->date) ? $lesson->date : '',
                'start_time'    => $start_time,
                'end_time'      => $end_time,
                'held'          => $lesson->attended(),
                'instructor_id' => isset($lesson->instructor->id) ? $lesson->instructor->id : '-1',
                'id'            => isset($lesson->id) ? $lesson->id : '-1',
                'action'        => $action,
            ];
        }

        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordWithFilter,
            'aaData' => $data,
        ];

        //print_r($response);
        return Response::json($response);
    }

    public function create(Request $request)
    {
        $route = 'lessons.store';

		$programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
		$classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();
		$courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
		$semesters = SemesterHelpers::getSemestersInArrayOnlyTitleId();
		$groups = GroupHelpers::getGroupsInArrayOnlyTitleId();

        return view(
            'back.lessons._partials.create',
            compact(
				'classrooms',
                'route',
                'programs',
				'courses',
				'semesters',
				'groups'
			)
        );
    }

    public function createMulti()
    {
        $route = 'lessons.store.multi';

        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
        $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();
		$courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
		$semesters = SemesterHelpers::getSemestersInArrayOnlyTitleId();
		$groups = GroupHelpers::getGroupsInArrayOnlyTitleId();

        return view(
            'back.lessons._partials.create-multiple-lessons',
            compact(
                'classrooms',
                'route',
                'programs',
				'courses',
				'semesters',
				'groups',
                //'instructors'
            )
        );
    }

    public function getLessonsDetails($payload)
    {
        switch ($payload['get']) {

            case 'program':
                // Get Semester's programs
                $programs = [];
                if($semster = Semester::find($payload['from']['fieldValue'])){
                    $programs = $semster->programs->pluck('title','id')->toArray();
                }
                $html = view('back.lessons.create-lessons.programs-list', [
                        'programs'  => $programs,
                        'lesson'    => isset($payload['lesson']) ? $payload['lesson'] : null,

                        ] )->render();
                $containter = "#programsList";
                break;


            case 'groups':
                $groups = [];
                $courses = [];

                if($program = Program::find($payload['from']['fieldValue'])){
                    $groups = $program->groups()
                    ->isActive()
                    ->withCount('students')
                    ->get();
                    /* ->pluck('title','id','students_count')->toArray(); */
                    $courses = $program->courses->pluck('title' , 'id')->toArray();
                }
                $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();

                if(isset($payload['lesson'])){
                    $lesson = Lesson::find($payload['lesson']);
                    $html = view('back.lessons.create-lessons.groups-list-edit', [
                        'lesson'            => $lesson,
                        'groups'            => $groups,
                        'courses'           => $courses,
                        'classrooms'        => $classrooms
                        ])->render();

                }else{
                    $html = view('back.lessons.create-lessons.groups-list', [
                        'groups'            => $groups,
                        'courses'           => $courses,
                        'classrooms'        => $classrooms
                        ])->render();
                }

                $containter = "#groupsList";
                break;

            case 'instructors':
                $instructors = [];
                if($course = Course::find($payload['from']['fieldValue'])){
                    $instructors = $course->instructors->pluck('name' , 'id')->toArray();
                }
                $html = view('back.lessons.create-lessons.instructors-list', [
                        'instructors'   => $instructors
                    ])->render();
                $containter = "#instructorsList";
                break;
            default:
                # code...
                break;
        }


        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => [
                        'container' => $containter,
                        'html'      => $html
                    ],
            ]
        );
    }

    /**
     * If group its related with program, courses related with this program are show
     * else if there is a course related with the group this course are show
     */
    public function createWithGroup(Group $group)
    {
        $route = 'lessons.store';

        $program_courses = null;
        $course = null;
        $groups = GroupHelpers::getGroupsInArrayOnlyTitleId();
        $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        if ($group->program) {
            $program = $group->program;
            $program_courses = CourseHelpers::getCoursesInArrayOnlyTitleId($program->courses);
        } else {
            $course = $group->course;
        }

        return view(
            'back.lessons._partials.createWithGroup',
            compact(
                'group',
                'groups',
                'classrooms',
                'route',
                'program_courses',
                'courses',
                'course'
            )
        );
    }

    /**
     * If group its related with program, courses related with this program are show
     * else if there is a course related with the group this course are show
     */
    public function createMultiWithGroup(Group $group)
    {
        $route = 'lessons.store.multi';

        $program_courses = null;
        $course = null;

        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
        $groups = GroupHelpers::getGroupsInArrayOnlyTitleId();
        $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();
       // $instructors = ModelHelpers::convertFirstNameLastnameInNameAssocWithId($group->instructors);

        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        if ($group->program) {
            $program = $group->program;
            $program_courses = CourseHelpers::getCoursesInArrayOnlyTitleId($program->courses);
        } else {
            $course = $group->course;
        }

        return view(
            'back.lessons._partials.createMultiWithGroup',
            compact(
                'group',
                'groups',
                //'instructors',
                'classrooms',
                'route',
                'program_courses',
                'courses',
                'course',
                'program',
                'programs'
            )
        );
    }

    public function createWithClassroom(Classroom $classroom)
    {
        $route = 'lessons.store';

        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
        $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();

        return view(
            'back.lessons._partials.createWithClassroom',
            compact('programs', 'courses', 'classroom', 'route', 'classrooms')
        );
    }

    public function createMultiWithClassroom(Classroom $classroom)
    {
        $route = 'lessons.store.multi';

        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
        $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();

        return view(
            'back.lessons._partials.createMultiWithClassroom',
            compact(
                'classroom',
                'classrooms',
                'route',
                'programs'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'program'        => 'required',
            //'course'         => 'required',
            'course'         =>  ['required',
                                    Rule::unique('App\Tenant\Models\Lesson','course_id')->where(function ($query) use ($request){
                                        return $query
                                            ->where('classroom_slot_id', $request->classroom_slot)
                                            ->where('date', $request->date);
                                    })
                                ],
            'group'          => 'required_without:semester',
            //'semester'       => 'required_without:group',
            'classroom'      => 'required',
            'classroom_slot' => 'required',
            'date'      => 'required',
        ]);

        $request['properties'] = [];

        try {
            DB::beginTransaction();

            $lesson = Lesson::create(
                $request->only(
                    'date',
                    'properties'
                )
            );


            $lesson->course()->associate($request->course)->save();
            $lesson->program()->associate($request->program)->save();

            /**if ($request->module) {
                $lesson->module()->associate($request->module)->save();
            }**/

            /**if (isset($request->group) && ! empty($request->group)) {
                $obj = Group::findOrFail($request->group);
            }

            elseif (isset($request->semester) && ! empty($request->semester)) {
                $obj = Semester::findOrFail($request->semester);
            }

            $lesson->lessoneable()->associate($obj)->save();**/

			if (isset($request->group) && ! empty($request->group)) {
                foreach ($request->group as $group_id) {
                    $group = Group::findOrFail($group_id);
                    $group->lessoneable()->save($lesson);
                }
            }

            if ($request->instructor) {
                $lesson->instructor()->associate($request->instructor)->save();
            }

            if ($request->classroom) {
                $lesson->classroom()->associate($request->classroom)->save();
            }

            $lesson->classroomSlot()->associate($request->classroom_slot)->save();

            DB::commit();

            return Response::json(
                [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['lesson_id' => $lesson->id],
                ]
            );
        } catch (\Exception $e) {
            DB::rollback();
            $lesson->delete();

            return Response::json(
                [
                    'status'   => 200,
                    'response' => $e->getMessage(),
                ]
            );
        }
    }

	public function storeMulti(Request $request)
    {

		$StartDate = $request->start_date;
		$ClassroomSlot = $request->classroom_slot;
        $courseId = $request->course;

        $request->validate([
            'program'        => 'required',
            'course'         =>  ['required',
                                    Rule::unique(Lesson::class,'course_id')->where(function ($query) use ($request){
                                        return $query
                                            ->where('classroom_slot_id', $request->classroom_slot)
                                            ->where('date', $request->start_date);
                                    })
                                ],
            'groups'          => 'required',
            'instructor'      => 'required',
            'classroom'      => 'required',
            'start_date'     => 'required',
            'end_date'       => 'required',
            'classroom_slot' => 'required',
            'week'           => 'required',
        ]);

		foreach ($request->week as $key => $week_day) {
			$dates_for_lesson = DateHelpers::allDaysOfWeekInRangeOfDates(
                $request->start_date,
                $request->end_date,
                $week_day
            );
			$this->saveLesson($dates_for_lesson, $request, $key);
		}
        return redirect()->route('lessons.index')->withSuccess('Lessons Created Successfully!');

    }

    public function show(Lesson $lesson)
    {
        $students = new Collection([]);
        foreach ($lesson->groups as $group) {
            $students = $students->merge($group->students()->with('attendances')->get());
        }
        $schedule = Schedule::find($lesson->classroomSlot->schedule_id);
        return view('back.lessons.show', compact('lesson', 'students','schedule'));
    }

    public function edit(Lesson $lesson)
    {

        $route = 'lessons.update';

        $lesson->load('classroom', 'course', 'instructor');

        // Lesson's program
        $program = $lesson->program;

        // get all programs
        $programs = Program::pluck('title' , 'id')->all();

        // get semesters
        $semesters = Semester::pluck('title' , 'id')->all();

        //Semester @TODO assign the semester to the lesson
        $semester = $program->semestereable()->get()->last();


        // get all Courses for the lesson's program
        $courses = $program->courses()->get()->pluck('title' , 'id');

        // get all for the courses
        $instructors = $lesson->course->instructors()->get()->pluck('name' , 'id');

        // get all Classrooms
        $classrooms = Classroom::pluck('title' , 'id')->all();


        $day_index = (new Carbon($lesson->date))->dayOfWeek;
        $day = Carbon::getDays()[$day_index];
        $classroom_slots = ClassRoomSlotHelpers::getClassroomSlotsInArrayOnlyTitleId(
            ClassroomSlot::where('classroom_id', $lesson->classroom->id)
                ->where('day', $day)
                ->join('schedules', 'schedule_id', '=', 'schedules.id')
                ->select('classroom_slots.id', 'schedules.label', 'schedules.start_time', 'schedules.end_time')
                ->orderBy('start_time', 'DESC')->get()
        );
        $type = explode('\\', $lesson->lessoneable_type);
        $type = end($type);
        switch ($type) {
            case 'Semester':
                $semesters = SemesterHelpers::getSemestersInArrayOnlyTitleId();
                break;
            case 'Group':
                $groups = GroupHelpers::getGroupsInArrayOnlyTitleId();
                break;
        }

        // get Groups
        $groups = $semester->groups()->isActive()->withCount('students')->get();

        return view('back.lessons._partials.edit',

            compact('semesters','courses','programs','route' , 'classrooms' , 'groups' , 'program' , 'lesson' , 'instructors' , 'classroom_slots' , 'semester'));
    }


    /* public function edit(Lesson $lesson)
    {
        $route = 'lessons.store';

        $lesson->load('classroom', 'course', 'instructor');

        $program = $lesson->program;

        $semesters = $program ? SemesterHelpers::programHaveSemesters($program) : null;

        $have_semesters = (bool) $semesters;

        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
        $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();


		//Get all lessoneables
        $lessoneables = Lessoneable::all();
        $lessonID = $lesson->id;

		//Fetch all Lessoneables by using Lesson Id
        $lessoneable_selected = LessoneableController::getLessoneableByLessonId($lessonID, $lessoneables);

        //Merge lessoneable and group table - display group name through lessoneable id
        $group_lessons = LessoneableController::getGroupNameByLessoneableId($lessoneable_selected);
        $group_lessons_selected = LessoneableController::getSelectedGroupsInArrayOnlyTitleId($group_lessons);

		$groups = GroupHelpers::getGroupsInArrayOnlyTitleId();
        $semesters = SemesterHelpers::getSemestersInArrayOnlyTitleId();

        $instructors = ModelHelpers::convertFirstNameLastnameInNameAssocWithId(
            collect([$lesson->instructor])->merge($lesson->course->instructors)
        );

        $day_index = (new Carbon($lesson->date))->dayOfWeek;
        $day = Carbon::getDays()[$day_index];
        $classroom_slots = ClassRoomSlotHelpers::getClassroomSlotsInArrayOnlyTitleId(
            ClassroomSlot::where('classroom_id', $lesson->classroom->id)
                ->where('day', $day)
                ->join('schedules', 'schedule_id', '=', 'schedules.id')
                ->select('classroom_slots.id', 'schedules.label', 'schedules.start_time', 'schedules.end_time')
                ->orderBy('start_time', 'DESC')->get()
        );

        $type = explode('\\', $lesson->lessoneable_type);
        $type = end($type);
        switch ($type) {
            case 'Semester':
                $semesters = SemesterHelpers::getSemestersInArrayOnlyTitleId();
                break;
            case 'Group':
                $groups = GroupHelpers::getGroupsInArrayOnlyTitleId();
                break;
        }
        return view(
            'back.lessons._partials.edit',
            compact(
                'courses',
                'programs',
                'program',
                'classrooms',
                'route',
                'lesson',
                'groups',
                'semesters',
                'have_semesters',
                'type',
                'instructors',
                'classroom_slots',
				'lessonID',
				'lessoneables',
				'lessoneable_selected',
                'group_lessons',
                'group_lessons_selected'
            )
        );
    } */

    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'program'        => 'required',
            'course'         => 'required',
            'groups'          => 'required',
            'semester'       => 'required',
            'instructor'     => 'required',
            'classroom'      => 'required',
            'classroom_slot' => 'required',
            'date'           => 'required',
        ]);
        try {
            DB::beginTransaction();
            $lesson->update($request->only('date'));
            $lesson->course()->associate($request->course)->save();

            if ($request->filled('module')) {
                $lesson->module()->associate($request->module)->save();
            }


			if ($request->filled('groups')) {
                $lesson->groups()->sync($request->groups);
            } else {
                $lesson->groups()->sync->sync([]);
            }

            //$lesson->lessoneable()->associate($obj)->save();

            if ($request->filled('instructor')) {
                $lesson->instructor()->associate($request->instructor)->save();
            }

            if ($request->filled('classroom')) {
                $lesson->classroom()->associate($request->classroom)->save();
            }
            if($request->filled('classroom_slot')){
                $lesson->classroomSlot()->associate($request->classroom_slot)->save();
            }

            DB::commit();

            return redirect()->route('lessons.index')->withSuccess(__('updated Successfully!'));

        } catch (\Exception $e) {
            dd($e->getMessage());

            DB::rollback();

            return redirect()->route('lessons.index')->withError(__('Something went wrong!'));


        }
    }

    public function bulkEdit(Request $request)
    {
        $lessons = $request->selected;
        $route = 'lessons.bulk.update';
        $action = $request->filled('action') ? $request->action : 'bulkEdit';
        switch ($action) {
            case 'editSemester':
                $semesters = Semester::pluck('title' , 'id')->all();
                $action = "updateSemester";
                return view(
                    'back.lessons._partials.bulk-edit-semesters',
                    compact('semesters' , 'route' , 'action' , 'lessons')
                );
                break;

            default:
                $route = 'lessons.bulk.update';
                $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
                $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();
                $action = "bulkEdit";
                return view(
                    'back.lessons._partials.bulk-edit',
                    compact(
                        'lessons' ,
                        'classrooms',
                        'route',
                        'programs',
                        'action'
                    )
                );
                break;
        }
    }

    public function bulkUpdate(Request $request)
    {

        $lessons = json_decode($request->get('lessons'),true);
        $lessons= Lesson::whereIn('id',explode(",",$lessons));
        $action = $request->filled('action') ? $request->action : 'bulkUpdate';

        switch ($action) {
            case 'updateSemester':
                // Get the cohots Groups
                $semester = Semester::find( (int) $request->semester);
                $semsterGroups = $semester->groups()->isActive()
                        ->get()->pluck('id')->toArray();

                foreach ($lessons->get() as $lesson) {

                        $program = $lesson->program;

                        $groups = $program->groups()->isActive()
                        ->whereHas('schedules' , function($builder) use ($lesson){
                            $builder->where('schedules.id', $lesson->classroomSlot->schedule_id);
                        })
                        ->get()
                        ->pluck('id')->toArray();

                        $allGroups = array_intersect($semsterGroups , $groups);
                        $lesson->groups()->sync($allGroups);
                        $lesson->save();

                    }
                    return Response::json(
                        [
                            'status'   => 200,
                            'response' => 'success',
                            'extra'    => [
                                'data_table' => 'lessons_table',
                                'message'    => __('Updated Successfully!')
                            ],
                        ]
                    );

                break;

            default:
            $data = $this->constructBulkUpdateData($request);
            if(!empty($data)){
                try {
                    $lessons->update($data);
                    return Response::json(
                        [
                            'status'   => 200,
                            'response' => 'success',
                            'extra'    => [
                                'data_table' => 'lessons_table',
                                'message'    => __('Updated Successfully!')
                            ],
                        ]
                    );
                } catch (\Exception $e) {
                    return Response::json(
                        [
                            'status'   => 419,
                            'response' => 'faild',
                            'extra'    => [
                                'message' => __('Something went wrong!')
                            ],
                        ]
                    );
                }
            }
            break;
        }


    }

    protected function constructBulkUpdateData(Request $request)
    {
        $data = [];
        foreach ($this->bulkEditProps as $key => $value) {
            if($request->filled($value)){
                if($value == 'classroom_slot'){
                    $value = $request->{$value};
                    $data[$key] = reset($value);
                }else{
                    $data[$key] = $request->{$value};
                }
            }
        }
        return array_filter($data);
    }

    public function bulkDestroy(Request $request)
    {
        $lessons= Lesson::whereIn('id',$request->selected);
        if ($response = $lessons->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'data_table' => 'lessons_table',
                        'message'    => __('Deleted Successfully!')
                    ],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 419,
                    'response' => 'faild',
                    'extra'    => ['message' => 'Something went wrong!'],
                ]
            );
        }
    }


    public function destroy(Lesson $lesson)
    {
        if ($response = $lesson->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $lesson->id],
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

    protected function saveLesson($dates_for_lesson, Request $request, $key)
    {
        try {
            DB::beginTransaction();

            foreach ($dates_for_lesson as $week_day => $dates) {
                foreach ($dates as $date) {
                    $lesson = Lesson::create(['date' => $date, 'properties' => []]);

                    $lesson->course()->associate($request->course)->save();
                    $lesson->program()->associate($request->program)->save();

                    if ($request->module) {
                        $lesson->module()->associate($request->module)->save();
                    }

                    /**if (isset($request->group) && ! empty($request->group) && count($request->group)) {
                        foreach ($request->group as $group_id) {
                            $group = Group::findOrFail($group_id);
                            $group->lessoneable()->save($lesson);
                        }
                    }**/

                    if ($request->filled('groups')) {
                        foreach ($request->groups as $group_id) {
                            $group = Group::findOrFail($group_id);
                            $group->lessoneable()->save($lesson);
                        }
                    }
                    if ($request->instructor) {
                        $lesson->instructor()->associate($request->instructor)->save();
                    }
                    if ($request->classroom) {
                        $lesson->classroom()->associate($request->classroom)->save();
                    }

                    $lesson->classroomSlot()->associate($request->classroom_slot[$key])->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            $lesson->delete();

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                ]
            );
        }
    }

    public function lessonDownloadIndex(Request $request)
    {
        $searchValue        = $request->search;
        $file               = $request->file;

        //# Custom Field value
        $start_date     = $request->start_date;
        $end_date       = $request->end_date;
        $instructors    = $request->instructors;
        $classrooms     = $request->classrooms;
        $courses        = $request->courses;

        //# Fetch records
        $lessons = Lesson::with('lessoneable', 'course', 'attendances', 'classroomSlot', 'instructor', 'classroom')
            ->join('instructors', 'lessons.instructor_id', '=', 'instructors.id')
            ->select([
                'lessons.*',
                'lessons.id AS id',
                'lessons.id AS lesson_id',
                'date',
                'course_id',
                'classroom_slot_id',
                'instructor_id',
                'classroom_id',
                'instructors.id as instructor_id',
                'instructors.first_name',
                'instructors.last_name',
                'instructors.email',
            ]);


        //# Search
        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';

            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('lessons.date', [$start_date, $end_date]);
            };
            $lessons->where($searchDatesRange);
        }

        if (isset($instructors) && !empty($instructors)) {
            $searchInstructors = function ($query) use ($instructors) {
                $query->whereIn('lessons.instructor_id', explode(",", $instructors));
            };
            $lessons->where($searchInstructors);
        }

        if (isset($classrooms) && !empty($classrooms)) {
            $searchClassroom = function ($query) use ($classrooms) {
                $query->whereIn('lessons.classroom_id', explode(",", $classrooms));
            };
            $lessons->where($searchClassroom);
        }

        if (isset($courses) && !empty($courses)) {
            $searchCourses = function ($query) use ($courses) {
                $query->whereIn('lessons.course_id', explode(",", $courses));
            };
            $lessons->where($searchCourses);
        }

        if ($searchValue != '') {
            $searchQuery = function ($query) use ($searchValue) {
                $query->whereHas('instructor', function ($query) use ($searchValue) {
                    return $query->where('first_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('last_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('email', 'like', '%'.$searchValue.'%');
                });
            };
            $lessons->where($searchQuery);
        }

        $lessons = $lessons->get();

        $data = [];

        $start_time = '';
        $end_time = '';
        $schedule = Schedule::get();
        foreach ($lessons as $lesson) {
            foreach ($schedule as $s) {
                if ($s->id == $lesson->classroomSlot->schedule_id) {
                    $end_time = QuotationHelpers::amOrPm($s->end_time);
                    $start_time = QuotationHelpers::amOrPm($s->start_time);
                }
            }

            $data[] = [
                'title'         => "Lesson",
                'instructor'    => isset($lesson->instructor) ? $lesson->instructor->name : '',
                'classroom'     => isset($lesson->classroom->title) ? $lesson->classroom->title : '',
                'course'        => isset($lesson->course->title) ? $lesson->course->title : '',
                'date'          => isset($lesson->date) ? $lesson->date : '',
                'start_time'    => $start_time,
                'end_time'      => $end_time,
                'held'          => $lesson->attended(),
            ];
        }

        $headings = [
            __('Title'),
            __('Instructor'),
            __('Classroom'),
            __('Course'),
            __('Date'),
            __('Start Time'),
            __('End Time'),
            __('Held'),
        ];

        $export = new LessonsExport($data, $headings);

        $file_name = 'lessons.xlsx';

        if ($file === 'csv') {
            $file_name = 'lessons.csv';
        }

        return Excel::download($export, $file_name);
    }

    public function lessonAttendancesExport(Lesson $lesson)
    {
        $data = [];
        $attendances = $lesson->attendances;
        foreach ($attendances as $attendance) {
            $attendanceData = [];
            $attendanceData['name'] = $attendance->student ? $attendance->student->first_name .' '. $attendance->student->last_name : '';
            $attendanceData['date'] = $attendance->lesson->date;
            $attendanceData['title'] = $attendance->lesson->course->title;
            $attendanceData['status'] = $attendance->status;
            array_push($data, $attendanceData);
        }
        $headings = [
            __('Name'),
            __('Date'),
            __('Title'),
            __('Status')
        ];
        $export = new AttendancesExport($data, $headings);
        $file_name = 'attendances_' . time() . '.xlsx';
        return Excel::download($export, $file_name);
    }

    public function lessonAttendancesUpdate(Lesson $lesson, Request $request)
    {
        foreach ($request->except('_token') as $attendance_id => $status) {
            $attendance = Attendance::find($attendance_id);
            if($attendance->status != $status) {
                $attendance->status = $status;
                $attendance->save();
            }
        }
        return redirect()->back()->with('message', 'Attendances Was Saved Successfully!');
    }

}
