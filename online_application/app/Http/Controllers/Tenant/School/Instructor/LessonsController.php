<?php

namespace App\Http\Controllers\Tenant\School\Instructor;

use App\Exports\AttendancesExport;
use App\Helpers\Application\ProgramHelpers;
use App\Helpers\Date\DateHelpers;
use App\Helpers\School\ClassroomHelpers;
use App\Helpers\School\ClassRoomSlotHelpers;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\GroupHelpers;
use App\Helpers\School\SemesterHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\Tenant\Models\Attendance;
use App\Tenant\Models\Classroom;
use App\Tenant\Models\ClassroomSlot;
use App\Tenant\Models\Course;
use App\Tenant\Models\Group;
use App\Tenant\Models\Lesson;
use App\Tenant\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class LessonsController extends Controller
{

    public function index($school, $course = null)
    {
        $lessons = Lesson::select([
            'id',
            'classroom_id',
            'date',
            'classroom_slot_id',
            'course_id',
        ])->with('classroomSlot', 'lessoneable', 'course')
        ->where('instructor_id', auth()->guard('instructor')->user()->id);

        if ($course != null) {
            $lessons->where('course_id', $course);
        }

        $lessons = $lessons->get();

        $schedule = Schedule::get();
        $result = [];
        try {
            foreach ($lessons as $lesson) {
                foreach ($schedule as $s) {
                    if ($s->id == $lesson->classroomSlot->schedule_id) {
                        $start_time = $s->start_time;
                        $end_time = $s->end_time;
                    }
                }

                $result[] = [
                    'id' => $lesson->id,
                    'resourceId' => $lesson->classroom_id,
                    'start' => Carbon::createFromTimestamp(strtotime($lesson->date.$start_time))->toDateTimeString(),
                    'end' => Carbon::createFromTimestamp(strtotime($lesson->date.$end_time))->toDateTimeString(),
                    'course' => $lesson->course->title,
                    'courseId' => $lesson->course->id,
                    'type' => 'Lesson',
                ];
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return Response::json($result);
    }

    public function attendances($school, Lesson $lesson, $action)
    {
        $course = $lesson->course;
        $students = new Collection([]);
        foreach ($lesson->groups as $group) {
            $students = $students->merge($group->students);
        }
        foreach ($students as $student) {
            $groupStudent = Group::find($student->pivot->group_id);
            $student["group"] = $groupStudent;
            if(isset($lesson)) {
                $studentAttendance = Attendance::where('lesson_id', $lesson->id)
                                                ->where('student_id', $student->id)->first();
                $student["attendance"] = $studentAttendance;
            } else {
                $student["attendance"] = null;
            }
        }
        if($action == "view") {
            return view('front.instructor._partials.components.lesson.attendances', compact('lesson', 'course'));
        } elseif($action == "edit") {
            return view('front.instructor._partials.components.lesson.add-attendances', compact('lesson', 'course',
            'students'));
        } elseif($action == "export") {
            $data = [];
            $attendances = $lesson->attendances;
            foreach ($attendances as $attendance) {
                $attendanceData = [];
                $attendanceData['name'] = $attendance->student->name;
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
    }

    public function updateAttendances($school, Lesson $lesson, Request $request)
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
