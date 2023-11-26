<?php

namespace App\Helpers\School;

use App\Tenant\Models\Instructor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class InstructorHelpers
{
    public static function getInstructorsInArrayOnlyNameId()
    {
        $instructors = Arr::pluck(Instructor::select("id" , DB::raw("CONCAT(instructors.first_name,' ',instructors.last_name) as full_name"))->get()->toArray(), 'full_name', 'id');

		return $instructors;
    }


    public static function courseDetailsByLessons($instructor,$course)
    {

        $statrt_date    = 'No Lessons';
        $end_date       = 'No Lessons';
        $schedule       = 'N/A';
        $status         = "PENDING";
        $classroomTitle = 'N/A';

        $lessons = $instructor->lessons()
        ->where('course_id' , $course->id)->get();

        if($lessons->count()){
            $statrt_date    = $lessons->first()->date;
            $end_date       = $lessons->last()->date;

            if($classroomSlot  = $lessons->last()->classroomSlot){
                $schedule = date('h:i A', strtotime($classroomSlot->start_time));
                $classroomTitle = $classroomSlot->classroom->title;
            }

            // Getting Status
            if(now() < $statrt_date) {
                $status = "PENDING";
            } elseif (now() > $statrt_date && now() < $end_date) {
                $status = "IN PROGRESS";
            } else {
                $status = "FINISHED";
            }
        }

        return [
            'start_date'        => $statrt_date,
            'end_date'          => $end_date,
            'status'            => $status,
            'schedule'          => $schedule,
            'classroomTitle'    => $classroomTitle
        ];
    }
}
