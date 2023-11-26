<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Attendance;
use App\Tenant\Models\Lesson;
use App\Tenant\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LessonAttendancesController extends Controller
{

    public function edit(Request $request, Lesson $lesson, Student $student)
    {
        $route = 'lesson.attendance.update';
        $studentAttendance = Attendance::where('lesson_id', $lesson->id)
                                                ->where('student_id', $student->id)->first();
        $student["attendance"] = $studentAttendance;
        return view('back.students._partials.lesson-attendance-edit-form', compact('lesson', 'student', 'route'));
    }

    public function update(Request $request, Lesson $lesson, Student $student)
    {
        $request->validate(
            [
                'status' => 'required',
            ]
        );

        $studentAttendance = Attendance::where('lesson_id', $lesson->id)
                                                ->where('student_id', $student->id)->first();

        if (isset($studentAttendance)) {
            $studentAttendance->status = $request->status;
            $studentAttendance->save();
        } else {
            $studentAttendance = new Attendance();
            $studentAttendance->lesson_id = $lesson->id;
            $studentAttendance->student_id = $student->id;
            $studentAttendance->instructor_id = auth()->guard('instructor')->user()->id ?? $request->instructor_id;
            $studentAttendance->status = $request->status;
            $studentAttendance->save();
        }
        
        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => ['attendance_id' => $studentAttendance->id],
            ]
        );
    }

    public function bulkEdit(Request $request, Lesson $lesson)
    {
        $students = $request->selected;
        $route = 'lesson.attendances.bulk.update'; 
        return view('back.students._partials.lesson-attendances-bulk-edit', compact('lesson', 'students', 'route'));
    }
	
    public function bulkUpdate(Request $request, Lesson $lesson)
    {
	    $students = json_decode($request->get('students'), true);
        $students = explode(",",$students);
		
        $request->validate(
            [
                'status' => 'required',
            ]
        );

        foreach ($students as $sutdent_id) {
            $studentAttendance = Attendance::where('lesson_id', $lesson->id)
                                                ->where('student_id', $sutdent_id)->first();
            if (isset($studentAttendance)) {
                $studentAttendance->status = $request->status;
                $studentAttendance->save();
            } elseif($request->status != "select a status") {
                $studentAttendance = new Attendance();
                $studentAttendance->lesson_id = $lesson->id;
                $studentAttendance->student_id = $sutdent_id;
                $studentAttendance->instructor_id = auth()->guard('instructor')->user()->id ?? $request->instructor_id;
                $studentAttendance->status = $request->status;
                $studentAttendance->save();
            }
        }
            
        return Response::json(
                [
                    'status' => 200,
                    'response' => 'success',
                    'extra' => [
                        'data_table' => 'attendances_table',
                        'students'=> $students,
                        'message'    => __('Updated Successfully!')
                    ],
                ]
            );
		
		
    }

    public function updateOrCreateAttendances(Request $request, Lesson $lesson)
    {
        foreach ($request->except('_token') as $sutdent_id => $status) {
            $studentAttendance = Attendance::where('lesson_id', $lesson->id)
                                                ->where('student_id', $sutdent_id)->first();
            if (isset($studentAttendance)) {
                $studentAttendance->status = $status;
                $studentAttendance->save();
            } elseif($status != "select a status") {
                $studentAttendance = new Attendance();
                $studentAttendance->lesson_id = $lesson->id;
                $studentAttendance->student_id = $sutdent_id;
                $studentAttendance->instructor_id = auth()->guard('instructor')->user()->id ?? $request->instructor_id;
                $studentAttendance->status = $status;
                $studentAttendance->save();
            }
        }

        return redirect()->back()->with(['message'=>'Attendances Was Saved Successfully!', 'students']);
    }

}