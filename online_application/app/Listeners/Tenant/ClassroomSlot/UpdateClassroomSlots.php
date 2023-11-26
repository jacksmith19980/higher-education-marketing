<?php

namespace App\Listeners\Tenant\ClassroomSlot;

use App\Events\Tenant\Classroom\ClassroomUpdated;
use App\Tenant\Models\ClassroomSlot;

class UpdateClassroomSlots
{
    public function __construct()
    {
        //
    }

    public function handle(ClassroomUpdated $event)
    {
        $slots = $event->getSlots();
        $classroom = $event->getClassroom();

        //dd($slots);
        //dd(array_diff(array_column($classroom->classroomSlots->toArray(), 'id'), array_column($slots, 'updating')), array_column($classroom->classroomSlots->toArray(), 'id'), array_column($slots, 'updating'));
        $slots_to_delete = array_diff(
            array_column($classroom->classroomSlots->toArray(), 'id'),
            array_column($slots, 'updating')
        );

        foreach ($slots_to_delete as $slot_to_delete) {
            ClassroomSlot::find($slot_to_delete)->delete();
        }

        foreach ($slots as $slot) {
            if ($slot['updating'] === null) {
                $classroom_slot = ClassroomSlot::create([
                    'day'         => $slot['day'],
                    'schedule_id' => $slot['schedule_id'],
                    /*'start_time' => $slot['start_time'],
                    'end_time'   => $slot['end_time']*/
                ]);
                $classroom_slot->classroom()->associate($classroom)->save();
            } else {
                $classroom_slot = ClassroomSlot::findOrFail($slot['updating']);
                $classroom_slot->day = $slot['day'];
                $classroom_slot->schedule_id = $slot['schedule_id'];
                /*$classroom_slot->start_time = $slot['start_time'];
                $classroom_slot->end_time = $slot['end_time'];*/
                $classroom_slot->save();
            }
        }
    }
}
