<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\School\ClassroomHelpers;
use App\Helpers\School\ClassRoomSlotHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ClassroomRepositoryInterface;
use App\Tenant\Models\Classroom;
use App\Tenant\Models\ClassroomSlot;
use App\Tenant\Models\Lesson;
use App\Tenant\Models\Schedule;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;

class ClassroomSlotController extends Controller
{
    protected $classroomRepository;

    public function __construct(
        ClassroomRepositoryInterface $classroomRepository
    ) {
        $this->middleware('plan.features:sis');
        $this->classroomRepository = $classroomRepository;
    }

    public function create($payload)
    {
        $data = [];
        $day = $payload['day'];
        $schedules = Schedule::get();
        $html = view('back.classrooms._partials.slot-row', compact('data', 'schedules', 'day'))->render();

        return Response::json([
            'status'   => 200,
            'response' => 'success',
            'extra'    => ['html' => $html],
        ]);
    }

    public function createRepeater($payload)
    {
        $schedules = Schedule::get();
        $html = view('back.classrooms._partials.slot-repeater-row', ['order' => $payload['order']], compact('schedules'))->render();

        return Response::json([
            'status'   => 200,
            'response' => 'success',
            'extra'    => ['html' => $html],
        ]);
    }

    public function getClassrooms(Request $request)
    {
        if ($request->classroom) {
            $classrooms = Classroom::select(['id', 'title'])->where(['id' => $request->classroom])->get();
        } elseif ($request->campus) {
            $classrooms = Classroom::select(['id', 'title'])->where(['campus_id' => $request->campus])->get();
        } else {
            $classrooms = Classroom::all(['id', 'title']);
        }

        return Response::json($classrooms);
    }

    public function getClassroomSlots(Request $request)
    {
        $lessons = Lesson::select([
            'id',
            'classroom_id',
            'date',
            'classroom_slot_id',
            'course_id',
        ])->with('classroomSlot', 'lessoneable', 'course');

        $schedule = Schedule::get();

        if ($request->classroom) {
            $lessons->where(['classroom_id' => $request->classroom]);
        } elseif ($request->campus) {
            $classrooms = Classroom::select(['id'])->where(['campus_id' => $request->campus])->get();

            $lessons->whereIn('classroom_id', $classrooms);
        }

        if ($request->start && $request->end) {
            $lessons->whereBetween('date', [$request->start, $request->end]);
        }

        $result = [];
        try {
            foreach ($lessons->get() as $lesson) {
                foreach ($schedule as $s) {
                    if ($s->id == $lesson->classroomSlot->schedule_id) {
                        $start_time = $s->start_time;
                        $end_time = $s->end_time;
                    }
                }

                $result[] = [
                    'id' => $lesson->id,
                    'resourceId' => $lesson->classroom_id,
                    'start' => Carbon::createFromTimestamp(strtotime($lesson->date.$start_time))->toDateTimeString(),
                    'end' => Carbon::createFromTimestamp(strtotime($lesson->date.$end_time))->toDateTimeString(),
                    'course' => $lesson->course->title,
                    'type' => 'Lesson',
                ];
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return Response::json($result);
    }

    public function weekDaySlot($payload)
    {
        $html = view('back.lessons._partials.week_day_slot_row', ['order' => $payload['order']])->render();

        return Response::json([
            'status'   => 200,
            'response' => 'success',
            'extra'    => ['html' => $html],
        ]);
    }

    public function classroomSlots($payload)
    {
        /* $classroom_slots = ClassroomSlot::select(['id', 'start_time', 'end_time'])
             ->where(['day' => $payload['week_day'], 'classroom_id' => $payload['classroom']])
             ->get();*/

        /*$classroom_slots = ClassroomSlot::with('schedule')
            ->join('schedule', 'schedule_id', '=', 'schedule.id')
            ->where(['day' => $payload['week_day'], 'classroom_id' => $payload['classroom']])
            ->get();*/

        $classroom_slots = ClassroomSlot::where(['day' => $payload['week_day'], 'classroom_id' => $payload['classroom']])
            ->join('schedules', 'schedule_id', '=', 'schedules.id')
            ->select('classroom_slots.id', 'schedules.label', 'schedules.start_time', 'schedules.end_time')
            ->get();

        //$classroom_schedule = $classroom_slots['schedule_id'];
        $slots = ClassRoomSlotHelpers::getClassroomSlotsInArrayOnlyTitleId($classroom_slots);

        return Response::json([
            'status'   => 200,
            'response' => 'success',
            'extra'    => ['slots' => $slots],
        ]);
    }
}
