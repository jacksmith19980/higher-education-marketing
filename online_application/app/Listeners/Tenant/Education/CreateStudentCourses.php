<?php
namespace App\Listeners\Tenant\Education;

use Carbon\Carbon;
use App\Tenant\Models\Date;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use App\Tenant\Models\Education;
use Illuminate\Support\Facades\Auth;
use App\Helpers\School\CourseHelpers;
use App\Tenant\Models\EducationStatus;
use App\Events\Tenant\Education\ProgramPurchased;


class CreateStudentCourses
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
     * @param  ProgramPurchased  $event
     * @return void
     */
    public function handle(ProgramPurchased $event)
    {
        $program = $event->program;
        $status = $event->parent->status;

        $programDate = Date::find($event->dateId);

        if($event->courses){
            $courses = Course::whereIn('id' , $event->courses )->get();
        }else{
            $courses = $program->courses;
        }
        if($courses->count()){
            foreach ($courses as $course) {
                $date = CourseHelpers::getSuggestedDateByProgramDate($course , $programDate);

                $props = [
                    'program'   => $program->pluck('title' , 'id'),
                    'added_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ];
                $education  = Education::create([
                    'properties' => $props,
                    'status'     => $status,
                    'date_id'    => ($date) ? $date->id : null,
                    'campus_id'  => ($event->parent->campus_id) ? $event->parent->campus_id : null,
                    'parent_id'  => $event->parent->id,
                ]);
                $course->educations()->save($education);

                $groups = $this->getParentGroups($event->parent);

                $education->groups()->sync($groups);
                if($event->student->educations()->save($education)){


                    $educationStatus = new EducationStatus;
                    $educationStatus->status = $status;
                    $educationStatus->properties = $props;
                    $education->statuses()->save($educationStatus);
                }
            }
        }

    }


    protected function getParentGroups($parent)
    {
        $list = [];
        $allGroups = $parent->allGroups;
        foreach ($allGroups as $group) {
            $list[$group->id] = [
                'added_manually' => true,
                'properties'     => json_encode([
                    'added_by'       => Auth::guard('web')->user()->id,
                    'added_by_user'  => Auth::guard('web')->user()->name,
                ])
            ];
        }

        return $list;

    }

}
