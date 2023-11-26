<?php

namespace App\Listeners\Tenant\Education;

use Carbon\Carbon;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use App\Tenant\Models\Education;
use App\Tenant\Models\EducationStatus;
use App\Events\Tenant\Education\ProgramPurchased;
use App\Events\Tenant\Application\ApplicationSubmissionUpdated;

class UpdateStudentEducation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ApplicationSubmissionUpdated  $event
     * @return void
     */
    public function handle(ApplicationSubmissionUpdated $event)
    {
        $submission = $event->submission['data'];
        $data = $event->data['educationable'];
        $status = "purchased";
        foreach ($data as $key => $value) {

            if(isset($submission[$key]['programs'])){
                $educationable = Program::where('slug' , $submission[$key]['programs'])->first();
                $educationableType = 'Program';
            }elseif(isset($submission[$key]['courses'])){
                $educationable = Course::where('slug' , $submission[$key]['courses'])->first();
                $educationableType = 'Course';
            }
            // Check if the student has already purchased the Program/Course
            $education = $event->student
            ->educations()
            ->where('educationable_id' , $educationable->id)
            ->where('educationable_type' , get_class($educationable))
            ->where('date_id' , $submission[$key]['date_id'])
            ->first();
            if($education){
                return ;
            }

            $education = new Education();
            $education->properties = [
                'application'   => $event->application->pluck('title','id')->toArray(),
                'added_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            $education->status = $status;
            $education->date_id = $submission[$key]['date_id'];

            $educationable->educations()->save($education);
            if($event->student->educations()->save($education)){
                // Dispatch Program Purchased Event to add related Courses
                if($educationableType == 'Program'){
                    event( new ProgramPurchased($educationable) );
                }

                $educationStatus = new EducationStatus;
                $educationStatus->status = $status;
                $educationStatus->properties = $props;
                $education->statuses()->save($educationStatus);
            }
        }
    }
}
?>
