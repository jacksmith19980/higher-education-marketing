<?php
namespace App\Listeners\Tenant\Date;

use App\Tenant\Models\Group;
use Illuminate\Http\Request;
use App\Helpers\Date\DateHelpers;
use App\Events\Tenant\Date\DateCreated;
use App\Http\Controllers\Tenant\LessonController;


class CreateRelatedGroupAndLessons
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
     * @param  DateCreated  $event
     * @return void
     */
    public function handle(DateCreated $event)
    {
        $date = $event->date;
        $groupable = $date->dateable;

        if($event->request->entity_type == 'course'){
            $course = $date->dateable;
            $program = $course->programs->first();
        }

        if($groupable->campuses->count()){
            foreach ($groupable->campuses as $campus) {

                if($event->createGroup){
                    $groups = Group::updateOrCreate(
                    [
                        'campus_id'      => $campus->id,
                        'date_id'        => $date->id,
                        'groupable_id'   => $groupable->id,
                        'groupable_type' => get_class($groupable),
                    ],
                    [
                        'is_active'      => true,
                        'properties'     => [],
                        'title'          => $this->generateGroupTitle($groupable , $date)
                    ]
                    );
                }else{
                    // Inherite Program Group if exists
                    $groups = $program->groups()->whereHas('date' , function($query) use ($date){
                        $query->whereDate('start', '<=', $date->start)
                        ->whereDate('end', '>=', $date->end);

                    })->get();
                }
                // Create Lessons for Courses
                if($event->request->filled('create_lessons') && (bool) $event->request->create_lessons ){
                    $this->createLessons($groups , $event , $event->request);
                }
            }
        }
    }

    protected function generateGroupTitle($groupable , $date)
    {
        $title = str_replace(" " , "-" , ucwords($groupable->title));
        $startDate = date('Ymd' , strtotime($date->start));
        $schedule = $date->schedule->label;
        return $title.'-'.$startDate.'-'.$schedule;
    }

    protected function createLessons($groups, $event , $request)
    {
        $date = $event->date;
        $lessons = $event->lessons;

        if($event->request->entity_type == 'course'){
            $course = $date->dateable;
            $program = $course->programs->first();
        }

        foreach ($lessons as $key => $lesson) {

            $request = new Request([
                "program"           =>  ($program) ?  $program->id : null,
                "course"            => $course->id,
                "groups"            => $groups->pluck('id')->toArray(),
                "instructor"        => $lesson['instructor'],
                "classroom"         => $lesson['classroom'],
                "start_date"        => $date->start,
                "end_date"          => $date->end,
                "classroom_slot"    => $lesson['classroom_slot'],
            ]);
            $dates_for_lesson = DateHelpers::allDaysOfWeekInRangeOfDates(
                $date->start,
                $date->end,
                $lesson['week']
            );
			app(LessonController::class)->saveLesson($dates_for_lesson, $request, $key);
        }
    }
}
?>
