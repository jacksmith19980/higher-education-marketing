<?php

namespace App\Http\Controllers\Tenant;

use App\Exports\AttendancesExport;
use App\Helpers\Quotation\QuotationHelpers;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\GroupHelpers;
use App\Helpers\School\StudentHelpers;
use App\Helpers\School\AttendanceHelpers;
use App\Helpers\School\ClassroomHelpers;
use App\Helpers\School\InstructorHelpers;
use App\Helpers\School\ClassRoomSlotHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Attendance;
use App\Tenant\Models\Lesson;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Instructor;
use App\Tenant\Models\Group;
use App\Tenant\Models\Classroom;
use App\Tenant\Models\ClassroomSlot;
use App\Tenant\Models\Student;
use Auth;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Response;

class AttendanceController extends Controller
{

	public function index(School $school)
    {
        $params = [
            'modelName' => Attendance::getModelName(),
        ];

        $instructor = Auth::guard('instructor')->user();

        $groups = GroupHelpers::getGroupsInArrayOnlyTitleId();

        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        $lessons = Lesson::with('lessoneable', 'course')->where('instructor_id', $instructor->id)->paginate();

        $schools = School::all();

//        return view(
//            'front.attendances.index',
//            compact('instructor', 'lessons', 'groups', 'courses', 'params', 'school', 'schools')
//        );
        if (config('app.locale') == 'en') {
            $datatablei18n = 'English';
        }

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

        return view('front.attendances.index', compact('datatablei18n', 'instructor'));
    }

    public function create(School $school, Lesson $lesson)
    {
        $students = new Collection([]);
        foreach ($lesson->groups as $group) {
            $students = $students->merge($group->students()->with('attendances')->get());
        }
        //$page = Paginator::resolveCurrentPage('page');

        $page = request()->has('page') ? request()->page : (Paginator::resolveCurrentPage() ?: 1);
        $students = $this->paginate($students, 100, $page);
        //$students = $this->paginate($students, 25, $page);
        return view('front.attendances.create', compact('lesson', 'students', 'school'));
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ? : (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }

    public function storeNew(Request $request)
    {
        $start_date = $request->start_date;
        $course = $request->course;
        $student = $request->student;
        $instructor = $request->instructor;
        $status = $request->status;

        $lesson = Lesson::where('instructor_id', $instructor)->where('course_id', $course)->where('date', $start_date)->first();

        if(!$lesson or !$start_date  or !$course or !$student or !$instructor or !$status)
            return Response::json(
                [
                    'status' => 400,
                    'response' => 'error',
                    'text' => 'Lesson not found',
                ]
            );

        Attendance::create([
            'status' => $status,
            'lesson_id' => $lesson->id,
            'instructor_id' => $instructor,
            'student_id' => $student,
         ]);

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
            ]
        );
    }

    public function store(Request $request, School $school, Lesson $lesson)
    {
        $instructor = Auth::guard('instructor')->user();

        $request->validate([
            'attendance' => 'required',
        ]);

        foreach ($request->attendance as $key => $value) {
            $attendance = Attendance::create([
                'status' => $value,
                'lesson_id' => $lesson->id,
                'instructor_id' => $instructor->id,
                'student_id' => $key,
             ]);
        }

        return redirect(route('attendances.index', compact('school', 'lesson')))
            ->with('success', 'Attended group successfully!');
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, Attendance $attendance)
    {
        $route = 'attendances.update';

        return view(
            'back.students._partials.attendance-edit-form',
            compact('attendance', 'route')
        );
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate(
            [
                'status' => 'required',
            ]
        );

        $attendance->status = $request->status;

        $attendance->save();

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => ['attendance_id' => $attendance->id],
            ]
        );
    }

    public function destroy($id)
    {
        //
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
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $instructor_id = $request->instructor_id;

        $school = School::byUuid(session('tenant'))->firstOrFail();

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
            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('lessons.date', [$start_date, $end_date]);
            };

            $totalRecordWithFilter->where($searchDatesRange);

            $lessons->where($searchDatesRange);
        }

        if (isset($instructor_id) && $instructor_id != '') {
            $searchLessonSpecificInstructor = function ($query) use ($instructor_id) {
                $query->where('lessons.instructor_id', $instructor_id);
            };

            $totalRecordWithFilter->where($searchLessonSpecificInstructor);

            $lessons->where($searchLessonSpecificInstructor);
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
            $data[] = [
                'title'         => 'Lesson',
                'instructor'    => isset($lesson->instructor) ? $lesson->instructor->name : '',
                'classroom'     => isset($lesson->classroom->title) ? $lesson->classroom->title : '',
                'course'        => isset($lesson->course->title) ? $lesson->course->title : '',
                'date'          => isset($lesson->date) ? $lesson->date : '',
                'start_time'    => $start_time,
                'end_time'      => $end_time,
                'held'          => $lesson->attended(),
                'instructor_id' => isset($lesson->instructor->id) ? $lesson->instructor->id : '-1',
                'id'            => isset($lesson->id) ? $lesson->id : '-1',
                'route'         => route('attendances.create', [
                    'school' => $school,
                    'lesson' => $lesson,
                ]),
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

    public function showBackIndex()
    {
        //Fetch values students for filtering
        $students = StudentHelpers::getStudentsInArrayOnlyNameId();
        $instructors = InstructorHelpers::getInstructorsInArrayOnlyNameId();
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
        $classrooms = ClassroomHelpers::getClassroomsInArrayOnlyTitleId();
        $classroomslots = ClassRoomSlotHelpers::getClassroomSlotsInArrayOnlyTimeId();
        $statuses = AttendanceHelpers::getStatusesDoubleTitle();

        $datatablei18n = 'English';

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

	return view('back.attendances.index', compact(
		'datatablei18n',
		'instructors',
		'classrooms',
		'courses',
		'students',
		'classroomslots',
		'statuses'
	));
    }

    public function addNew()
    {
        //$route = "attendance.store.new";

	$students = StudentHelpers::getStudentsInArrayOnlyNameId();
	$instructors = InstructorHelpers::getInstructorsInArrayOnlyNameId();
    $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        return view('back.attendances._partials.add-new', compact(
			'students',
			'instructors',
            'courses'
	));
    }

    public function getAttendances(Request $request)
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
        $students       = $request->students;
        $statuses		= $request->statuses;
        $timeslots		= $request->timeslots;

        //$student_statuses   = $request->student_statuses;

        //# Total number of records without filtering
        /**$totalRecords = Lesson::select('count(*) as allcount')
            ->join('instructors', 'lessons.instructor_id', '=', 'instructors.id')
            ->count();**/

	$totalRecords = Attendance::all()->count();

        //# Fetch records
        /**$lessons = Lesson::with('lessoneable', 'course', 'attendances', 'classroomSlot', 'instructor', 'classroom')
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
        ->take($rowperpage);**/

	$attendances = Attendance::with('lesson','instructor','student')
		->join('lessons','attendances.lesson_id','=','lessons.id')
		->join('instructors','attendances.instructor_id','=','instructors.id')
		->join('students','attendances.student_id','=','students.id')
		->join('courses','lessons.course_id','=','courses.id')
		->join('classroom_slots','lessons.classroom_slot_id','=','classroom_slots.id')
		->select(
			'attendances.*',
            'attendances.id AS id',
			'attendances.status',
			'attendances.student_id',
			'attendances.instructor_id',
			'lessons.course_id',
			'students.id as student_id',
			'instructors.id as instructor_id',
			'instructors.first_name',
			'courses.title',
			'lessons.classroom_slot_id',
			'classroom_slots.day',
			'classroom_slots.start_time',
			'classroom_slots.end_time',
			)
			->skip($row)
			->take($rowperpage);

        $totalRecordWithFilter = Attendance::select('count(*) as allcount')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id');

        //# Search
        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';

            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('lessons.date', [$start_date, $end_date]);
            };
            $totalRecordWithFilter->where($searchDatesRange);
            $attendances->where($searchDatesRange);
        }

	if (isset($students) && !empty($students)  && is_array($students)) {
            $searchStudents = function ($query) use ($students) {
                $query->whereIn('attendances.student_id', array_map('intval', array_values($students)));
            };
            $totalRecordWithFilter->where($searchStudents);
            $attendances->where($searchStudents);
        }

        if (isset($instructors) && !empty($instructors)  && is_array($instructors)) {
            $searchInstructors = function ($query) use ($instructors) {
                $query->whereIn('attendances.instructor_id', array_map('intval', array_values($instructors)));
            };
            $totalRecordWithFilter->where($searchInstructors);
            $attendances->where($searchInstructors);
        }

	if (isset($courses) && !empty($courses)  && is_array($courses)) {
            $searchCourses = function ($query) use ($courses) {
                $query->whereIn('lessons.course_id', array_map('intval', array_values($courses)));
            };
            $totalRecordWithFilter->where($searchCourses);
            $attendances->where($searchCourses);
        }

	if (isset($statuses) && !empty($statuses)  && is_array($statuses)) {
            $searchStatus = function ($query) use ($statuses) {
                $query->whereIn('status', $statuses);
            };
            $totalRecordWithFilter->where($searchStatus);
            $attendances->where($searchStatus);
        }

	if (isset($timeslots) && !empty($timeslots)  && is_array($timeslots)) {
            $searchTimeslots = function ($query) use ($timeslots) {
                $query->whereIn('lessons.classroom_slot_id', array_map('intval', array_values($timeslots)));
            };
            $totalRecordWithFilter->where($searchTimeslots);
            $attendances->where($searchTimeslots);
        }

        if ($searchValue != '') {
            $searchQuery = function ($query) use ($searchValue) {
                $query->whereHas('student', function ($query) use ($searchValue) {
                    return $query->where('first_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('last_name', 'like', '%'.$searchValue.'%');
       		});
            };

            //# Total number of record with filtering
            $totalRecordWithFilter->where($searchQuery);

            $attendances->where($searchQuery);
        }

        $attendances->orderBy($columnName, $columnSortOrder);

        $totalRecordWithFilter = $totalRecordWithFilter->count();
        $attendances = $attendances->get();
        $data = [];

        $start_time = '';
        $end_time = '';
        $schedule = Schedule::get();
        foreach ($attendances as $attendance) {

            $select= view('back.attendances._partials.select-row' , ['item' => $attendance])->render();

            $data[] = [
                'select'        => $select,
                'student'       => isset($attendance->student) ? $attendance->student->name : '',
                'student_id'    => isset($attendance->student) ? $attendance->student->id : '',
                'course'        => isset($attendance->lesson) ? $attendance->lesson->course->title : '',
                'instructor'    => isset($attendance->instructor) ? $attendance->instructor->name : '',
                'timeslot'      => isset($attendance->lesson) ? $attendance->lesson->classroomSlot->start_time.' - '.$attendance->lesson->classroomSlot->end_time : '',
				'date'			=> isset($attendance->lesson) ? $attendance->lesson->date : '',
                'status'        => isset($attendance->status) ? $attendance->status : '',
                'id'            => isset($attendance->id) ? $attendance->id : '-1',
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

    public function backEdit(Attendance $attendance)
    {

	$route = 'attendances.back.update';

	return view(
            'back.attendances._partials.edit',
            compact(
		'attendance',
		'route',
	    )
	);
    }

    public function backUpdate(Request $request, Attendance $attendance)
    {
	try {
            	DB::beginTransaction();

		$attendance = Attendance::find($request->route('attendance')->id);
		$attendance->status = $request->status;
		$attendance->save();

		DB::commit();

            	return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
			'status'	 => $request->status,
			'data_table' 	 => 'attendances_table',
			'attendance' 	 => $attendance,
			'id'		 => $request->route('attendance')->id,
			'message'    	 => __('Updated Successfully!')
		    ],
                ]
            	);

        } catch (\Exception $e) {
            DB::rollback();
            //$attendance->delete();
            return Response::json(
                [
                    'status'   => 200,
                    'response' => $e->getMessage(),
                ]
            );
        }
    }

    public function bulkEdit(Request $request)
    {
	$attendances = $request->selected;
	$route = 'attendances.bulk.update';

	return view(
        	'back.attendances._partials.bulk-edit',
            	compact(
			'attendances',
                	'route',
            	)
        );
     }

    public function bulkUpdate(Request $request)
    {
	$attendances = json_decode($request->get('attendances'), true);
        $attendances = Attendance::whereIn('id',explode(",",$attendances))->get();


	//$attendance->status = $request->status;
	//$attendance->save();

	//$attendances_obj = Attendance::find($request->attendances);
	//$attendances = json_decode($attendances_obj);

	$request->validate(
            [
                'status' => 'required',
            ]
        );

	foreach ($attendances as $attendance){

		$attendance->update(['status' => $request->status ]);
	}

	return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => [
					'data_table' => 'attendances_table',
					'attendances'=> $attendances,
                    'message'    => __('Updated Successfully!')
                ],
            ]
        );


    }

    public function backDestroy(Request $request, Attendance $attendance)
    {
	try{
        	DB::beginTransaction();

		$attendance = Attendance::where('id' , $request->id )->delete();

		DB::commit();

		return Response::json([
			'status'    => 200,
			'response'  => 'success',
			'extra'     => [
				'id'		 => $request->id,
				'removedId'  => $request->id,
			],
		]);

	} catch (\Exception $e) {
            DB::rollback();
            return Response::json(
                [
                    'status'   => 200,
                    'response' => $e->getMessage(),
                ]
            );
        }
    }

    public function bulkDestroy(Request $request, Attendance $attendance)
    {

	$attendance = Attendance::whereIn('id', $request->selected);
        if ($response = $attendance->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'data_table' => 'attendances_table',
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

    public function attendanceDownloadIndex(Request $request)
    {
        $searchValue        = $request->search;
        $file               = $request->file;

        //# Custom Field value
        $start_date     = $request->start_date;
        $end_date       = $request->end_date;
	$students 		= $request->students;
        $instructors    = $request->instructors;
        $classrooms     = $request->classrooms;
        $courses        = $request->courses;

        //# Fetch records
        $attendance_table = Attendance::with('lesson','instructor','student')
			->join('lessons','attendances.lesson_id','=','lessons.id')
			->join('instructors','attendances.instructor_id','=','instructors.id')
			->join('courses','lessons.course_id','=','courses.id')
			->join('classroom_slots','lessons.classroom_slot_id','=','classroom_slots.id')
			->select([
				'attendances.*',
                		'attendances.id AS id',
                		'status',
				'lessons.course_id',
				'instructors.first_name',
				'instructors.first_name',
				'courses.title',
				'classroom_slots.start_time',
				'classroom_slots.end_time',
			]);

        //# Search
        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';

            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('lessons.date', [$start_date, $end_date]);
            };
            $attendance_table->where($searchDatesRange);
        }

	if (isset($students) && !empty($students)) {
            $searchStudents = function ($query) use ($students) {
                $query->whereIn('attendances.student_id', explode(",", $students));
            };
            $attendance_table->where($searchStudents);
        }

	if (isset($instructors) && !empty($instructors)) {
            $searchInstructors = function ($query) use ($instructors) {
                $query->whereIn('attendances.instructor_id', explode(",", $instructors));
            };
            $attendance_table->where($searchInstructors);
        }

	if (isset($courses) && !empty($courses)) {
            $searchCourses = function ($query) use ($courses) {
                $query->whereIn('lessons.course_id', explode(",", $courses));
            };
            $attendance_table->where($searchCourses);
        }

	$attendance_table = $attendance_table->get();
	$data = [];

	foreach ($attendance_table as $attendance)
	{
		$data[] = [
			'id'			=> isset($attendance->id) ? $attendance->id : '-1',
			'student'  		=> isset($attendance->student) ? $attendance->student->name : '',
			'student_id'		=> isset($attendance->student) ? $attendance->student->id : '',
			'course'		=> isset($attendance->lesson) ? $attendance->lesson->course->title : '',
			'instructor'		=> isset($attendance->instructor) ? $attendance->instructor->name : '',
			'timeslot'		=> isset($attendance->lesson) ? $attendance->lesson->classroomSlot->start_time.' - '.$attendance->lesson->classroomSlot->end_time : '',
			'date'			=> isset($attendance->lesson) ? $attendance->lesson->date : '',
			'status'		=> isset($attendance->status) ? $attendance->status : '',
            	];
	}

        $headings = [
	    __('Attendance ID'),
            __('Student'),
	    __('Student ID'),
            __('Course'),
            __('Instructor'),
            __('Timeslot'),
            __('Date'),
            __('Status'),
        ];

        $export = new AttendancesExport($data, $headings);

        $file_name = 'attendances.xlsx';

        if ($file === 'csv') {
            $file_name = 'attendances.csv';
        }

        return Excel::download($export, $file_name);
    }

}
