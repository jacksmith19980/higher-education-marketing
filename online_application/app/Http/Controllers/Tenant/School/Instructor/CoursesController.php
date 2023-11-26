<?php

namespace App\Http\Controllers\Tenant\School\Instructor;

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
use Response;

class CoursesController extends Controller
{

    public function show(Request $request , $school, Course $course, $place = null, Lesson $lesson = null)
    {
        if(isset($place)) {
            $place = Crypt::decrypt($place);
        } else {
            $place = "students";
        }
        $instructor = auth()->guard('instructor')->user();
        $students = new Collection([]);
        $attendances = new Collection([]);
        $lessons = $course->lessons()->select([ 'id',
                                                'classroom_id',
                                                'date',
                                                'classroom_slot_id',
                                                'course_id',
                                            ])->with('classroomSlot', 'lessoneable', 'course','groups.students')
                                            ->where('instructor_id', $instructor->id)->get();

        foreach ($lessons as $l) {
            $attendances = $attendances->merge($l->attendances);
            foreach ($l->groups as $group) {
                $students = $students->merge($group->students);
            }
        }
        foreach ($students as $student) {
            $groupStudent = Group::find($student->pivot->group_id);
            $student["group"] = $groupStudent;
            if(isset($lesson)) {
                $studentAttendance = Attendance::where('lesson_id', $lesson->id)
                                                ->where('student_id', $student->id)
                                                ->where('instructor_id', $instructor->id)->first();
                $student["attendance"] = $studentAttendance;
            } else {
                $student["attendance"] = null;
            }
        }
        return view('front.instructor.dashboard.course-index', compact('course', 'students', 'attendances', 'lessons', 'lesson', 'place'));
    }

}
