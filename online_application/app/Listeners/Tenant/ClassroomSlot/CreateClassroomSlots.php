<?php

namespace App\Listeners\Tenant\ClassroomSlot;

use App\Events\Tenant\Classroom\ClassroomCreated;
use App\Tenant\Models\ClassroomSlot;

class CreateClassroomSlots
{
    public function __construct()
    {
        //
    }

    public function handle(ClassroomCreated $event)
    {
        $slots = $event->getSlots();
        $classroom = $event->getClassroom();

        if (count($slots) === 0) {
            return;
        }

        foreach ($slots as $slot) {
            $classroom_slot = ClassroomSlot::create([
                'day' => $slot['day'],
                'schedule_id' => $slot['schedule_id'],
                /*'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time']*/
            ]);
            $classroom_slot->classroom()->associate($classroom)->save();
        }
    }
}
