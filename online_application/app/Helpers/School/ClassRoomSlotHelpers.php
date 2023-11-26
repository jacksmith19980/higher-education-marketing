<?php

namespace App\Helpers\School;

use App\Helpers\Quotation\QuotationHelpers;
use App\Tenant\Models\ClassroomSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ClassRoomSlotHelpers
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function slotsPicked()
    {
        $schedule_id = array_filter($this->request->all('schedule_id')['schedule_id']);
        /*$start_time = array_filter($this->request->all('start_time')['start_time']);
        $end_time = array_filter($this->request->all('end_time')['end_time']);*/
        $updating = array_filter($this->request->all('updating')['updating']);

        $result = [];
        foreach (Carbon::getDays() as $day) {
            if (isset($schedule_id[$day])) {
                foreach ($schedule_id[$day] as $key => $item) {
                    $result[] = [
                        'day' => $day,
                        'schedule_id' => $schedule_id[$day][$key],
                        /*'start_time' => $start_time[$day][$key],
                        'end_time' => $end_time[$day][$key],*/
                        'updating' => $updating[$day][$key],
                    ];
                }
            }
        }

        return $result;
    }

    public function slotsPickedRepeater()
    {
        $week = $this->request->week;
        $schedule_id = $this->request->new_schedule_id;
        /*$start_time = $this->request->new_start_time;
        $end_time = $this->request->new_end_time;*/

        $result = [];
        foreach ($week as $key => $item) {
            /*dd($schedule_id[$key]);*/
            foreach ($item as $week_day) {
                $result[] = [
                    'day' => $week_day,
                    'schedule_id' => $schedule_id[$key],
                    /*'start_time' => $start_time[$key],
                    'end_time' => $end_time[$key]*/
                ];
            }
        }

        return $result;
    }

    public static function getClassroomSlotsInArrayOnlyTitleId($collection)
    {
        if ($collection->isEmpty()) {
            return [];
        }

        return $collection->map(
            function ($person) {
                return [
                    $person['id'],
                    $person['date'].
                    $person['label'].
                    ' ('.QuotationHelpers::amOrPm($person['start_time']).
                    ' - '.QuotationHelpers::amOrPm($person['end_time']).')',
                ];
            }
        )->reduce(
            function ($assoc, $keyValuePair) {
                list($key, $value) = $keyValuePair;
                $assoc[$key] = $value;

                return $assoc;
            }
        );
    }
    
    public static function getClassroomSlotsInArrayOnlyTimeId()
    {
        $classroom_slots = Arr::pluck(ClassroomSlot::select("id" , DB::raw("CONCAT(classroom_slots.start_time,' - ',classroom_slots.end_time) as slot"))
			->get()->toArray(), 'slot', 'id');
		$unique_slots = array_unique($classroom_slots);
		
		return $unique_slots;
    }
}
