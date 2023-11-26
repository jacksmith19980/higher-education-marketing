<?php

namespace App\Http\Controllers\Tenant\School\Instructor;


use App\Http\Controllers\Controller;
use App\Tenant\Models\Course;
use App\Tenant\Models\Student;

class StudentsController extends Controller
{

    public function show($school, Student $student, Course $course, $place)
    {
        $instructor = auth()->guard('instructor')->user();
        $lessonsIds = $course->lessons()->where('instructor_id', $instructor->id)->get()->pluck('id')->toArray();
        $studentAttendances = $student->attendances()->whereIn('lesson_id',  $lessonsIds)->get();
        return view('front.instructor._partials.components.student', compact('student', 'studentAttendances', 'place', 'course'));
    }

}
