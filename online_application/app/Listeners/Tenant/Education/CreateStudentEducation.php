<?php
namespace App\Listeners\Tenant\Education;

use Carbon\Carbon;
use App\Tenant\Models\Group;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use App\Http\Controllers\Tenant\EducationController;
use App\Events\Tenant\Application\ApplicationSubmitted;
use App\Events\Tenant\Application\ApplicationSubmissionEvent;

class CreateStudentEducation
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
     * @param  ApplicationSubmitted  $event
     * @return void
     */
    public function handle(ApplicationSubmissionEvent $event)
    {

        $status = "purchased";
        $submission = $event->submission['data'];

        $data = isset($event->data['educationable']) ? $event->data['educationable'] : false;


        if($data){

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
                ->where('date_id' , isset($submission[$key]['date']) ? $submission[$key]['date'] : null)
                ->first();
                if($education)
                {
                    continue;
                }
                $data = [
                    'props'     => [
                        'properties' => $submission[$key],
                        'meta'       => [
                            'application'   => $event->application->pluck('title','id')->toArray(),
                            'added_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                            'added_by'          => $event->student->name,
                            'added_by_user'     => $event->student->id,
                            'added_manually'    => false
                        ]
                    ],
                    'status'    => $status,
                    'date_id'   => (isset($submission[$key]['date']))? (int) $submission[$key]['date'] : null,
                    'campus_id' => (isset($submission[$key]['campus'])) ? $submission[$key]['campus'] : null,
                ];

                // get the Group
                $group = Group::where('campus_id', (int) $data['campus_id'])
                ->where('date_id' , $data['date_id'])
                ->pluck('title' , 'id')->toArray();

                $data['cohorts'] = array_keys($group);

                $response = app(EducationController::class)->createEducation($educationable,$event->student, $educationableType, array_filter($data));

                return $response;            }
        }

        return false;

    }
}
?>
