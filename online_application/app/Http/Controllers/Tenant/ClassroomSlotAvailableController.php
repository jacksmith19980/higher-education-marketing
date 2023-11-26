<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Date\DateHelpers;
use App\Helpers\School\ClassRoomSlotHelpers;
use App\Http\Controllers\Controller;
use App\Tenant\Models\ClassroomSlot;
use App\Tenant\Models\Lesson;
use App\Tenant\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClassroomSlotAvailableController extends Controller
{
    public function __construct()
    {
        $this->middleware('plan.features:sis');
    }

    public function index($payload)
    {
        $day_index = (new Carbon($payload['date']))->dayOfWeek;
        $day = Carbon::getDays()[$day_index];

        $lessons_id = Lesson::where('date', $payload['date'])->pluck('classroom_slot_id')->toArray();

        /*$classroom_slot = ClassRoomSlotHelpers::getClassroomSlotsInArrayOnlyTitleId(
            ClassroomSlot::where('classroom_id', $payload['classroom'])
                ->where('day', $day)
                //->whereNotIn('id', $lessons_id)
                ->orderBy('start_time')->get()
        );*/

        $classroom_slot = ClassRoomSlotHelpers::getClassroomSlotsInArrayOnlyTitleId(
            ClassroomSlot::where('classroom_id', $payload['classroom'])
                ->where('day', $day)
                ->join('schedules', 'schedule_id', '=', 'schedules.id')
                ->select('classroom_slots.id', 'schedules.label', 'schedules.start_time', 'schedules.end_time')
                //->whereNotIn('id', $lessons_id)
                ->orderBy('start_time')->get()
        );

        if (
            $html = view(
                'back.shared._partials.field_value',
                [
                'data'        => $classroom_slot,
                'name'        => 'classroom_slot',
                'label'       => 'Classroom Slot Available',
                'placeholder' => count($classroom_slot) < 1 ? 'No slot available' : 'Select a slot',
                ]
            )->render()
        ) {
            return response()->json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['html' => $html],
                ]
            );
        }
    }

    public function multi($payload)
    {
        $days = [];
        $slots = [];
        $result = [];
        foreach ($payload['week'] as $week_day) {
            $days = array_merge($days, DateHelpers::allDaysOfWeekInRangeOfDates(
                $payload['start_date'],
                $payload['end_date'],
                $week_day
            ));

            $slots[$week_day] = ClassroomSlot::select('start_time', 'end_time')
                ->where('classroom_id', $payload['classroom'])
                ->where('day', $week_day)
                ->orderBy('start_time')
                ->get()
                ->toArray();
        }

        foreach ($days as $week_day => $dates) {
            foreach ($dates as $date) {
                $temp = [];
                $classroom_slot_id_with_lesson = Lesson::select('classroom_slot_id')
                    ->where('date', $date)
                    ->pluck('classroom_slot_id')
                    ->toArray();

                $temp = array_merge($temp, ClassRoomSlotHelpers::getClassroomSlotsInArrayOnlyTitleId(
                    ClassroomSlot::where('classroom_id', $payload['classroom'])
                        ->where('day', $week_day)
                        ->whereNotIn('id', $classroom_slot_id_with_lesson)
                        ->orderBy('start_time')->get()
                ));
                $result[] = ['week' => $week_day, 'date' => $date, 'slots' => $temp];
            }
        }

        if (
            $html = view('back.shared._partials.field_value', [
                'data'        => $classroom_slot,
                'name'        => 'classroom_slot',
                'label'       => 'Classroom Slot Available',
                'placeholder' => count($classroom_slot) < 1 ? 'No slot available' : 'Select a slot',
            ])->render()
        ) {
            return response()->json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['html' => $html],
                ]
            );
        }
    }
}
