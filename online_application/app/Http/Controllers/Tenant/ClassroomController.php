<?php

namespace App\Http\Controllers\Tenant;

use App\Events\Tenant\Classroom\ClassroomCreated;
use App\Events\Tenant\Classroom\ClassroomUpdated;
use App\Helpers\School\CampusHelpers;
use App\Helpers\School\ClassRoomSlotHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\Repositories\Interfaces\ClassroomRepositoryInterface;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Classroom;
use App\Tenant\Models\ClassroomSlot;
use App\Tenant\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;

class ClassroomController extends Controller
{
    protected $campus_repository;
    protected $classroomRepository;

    public function __construct(
        CampusRepositoryInterface $campus_repository,
        ClassroomRepositoryInterface $classroomRepository
    ) {
        $this->middleware('plan.features:sis')
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        $this->campus_repository = $campus_repository;
        $this->classroomRepository = $classroomRepository;
    }

    public function index()
    {
        $params = [
            'modelName' => Classroom::getModelName(),
        ];

        $classrooms = $this->classroomRepository->all();
        $schedules = Schedule::get();

        return view('back.classrooms.index', compact('classrooms', 'schedules', 'params'));
    }

    public function create()
    {
        $campuses = CampusHelpers::getCampusesInArrayOnlyTitleId(($this->campus_repository->all())->toArray());
        $schedules = Schedule::get();
        $days = Carbon::getDays();

        return view('back.classrooms.create', compact('campuses', 'schedules', 'days'));
    }

    public function store(Request $request, ClassRoomSlotHelpers $classRoomSlotHelpers)
    {
        $request->validate(
            [
                'title'  => 'required|max:50',
                'campus' => 'required',
            ]
        );

        $campus = Campus::findOrFail($request->campus);

        $classroom = $this->classroomRepository->create([
            'title'      => $request->title,
            'properties' => [],
        ]);

        if (isset($request->week) && isset($request->new_schedule_id)) {
            event(new ClassroomCreated($classroom, $classRoomSlotHelpers->slotsPickedRepeater()));
        }

        /*if (isset($request->week) && isset($request->new_start_time) && isset($request->new_end_time)) {
            event(new ClassroomCreated($classroom, $classRoomSlotHelpers->slotsPickedRepeater()));
        }*/

        $classroom->campus()->associate($campus)->save();

        return redirect(route('classrooms.index'))
            ->with('success', "Classroom {$classroom->title} created successfully!");
    }

    public function show(Classroom $classroom)
    {
        $days = Carbon::getDays();

        $classroom_slots = $classroom->classroomSlots->mapToGroups(
            function ($item, $key) {
                return [$item['day'] => [$item['start_time'], $item['end_time']]];
            }
        )->toArray();

        $ranges = $this->hoursRange(32400, 72000, 60 * 60, 'h:i a');

        return view('back.classrooms.show', compact('classroom', 'days', 'classroom_slots', 'ranges'));
    }

    public function hoursRange($lower = 0, $upper = 86400, $step = 3600, $format = '')
    {
        $times = [];

        if (empty($format)) {
            $format = 'g:i a';
        }

        foreach (range($lower, $upper, $step) as $increment) {
            $increment = gmdate('H:i', $increment);

            list($hour, $minutes) = explode(':', $increment);

            $date = new \DateTime($hour.':'.$minutes);

            $times[(string) $increment] = $date->format($format);
        }

        return $times;
    }

    public function edit(Classroom $classroom)
    {
        $campuses = CampusHelpers::getCampusesInArrayOnlyTitleId(($this->campus_repository->all())->toArray());

        $days = Carbon::getDays();
        $schedules = Schedule::get();
        $classroom->load('classroomSlots');
        /*$cSlots = $classroom->classroomSlots->toArray();
        $i = 1;


        foreach ($cSlots as $key => $c) {
            $s = Schedule::get();
            foreach($s as $sc){
                $sch[] = $sc->start_time . '-' . $sc->end_time;

            }
            $cSch = $c['start_time'] . '-' . $c['end_time'];
            if(!in_array($cSch, $sch)){
                $label = "Schedule ". $i. str_random(3);
                $createSch = Schedule::create([
                    'label'         => $label,
                    'start_time'    => $c['start_time'],
                    'end_time'      => $c['end_time'],
                ]);

                $lastId = $createSch->id;

            }
            $i++;
        }*/

        //dd($label);
//        $classroom_slots = ClassroomSlot::where('classroom_id', $classroom->id)->get()->toArray();
//
//        $slots = [];
//        foreach ($classroom_slots as $key => $value) {
//            $slots[$value['start_time'] . '-' . $value['end_time']][$value['day']] = $value['day'];
//        }
//
//        return view('back.classrooms.edit', compact('campuses', 'classroom', 'days', 'slots'));

        return view('back.classrooms.edit', compact('campuses', 'classroom', 'days', 'schedules'));
    }

    public function update(Request $request, $id, ClassRoomSlotHelpers $classRoomSlotHelpers)
    {
        $request->validate([
            'title'  => 'required|max:50',
            'campus' => 'required',
        ]);

        $input = $request->only(['title', 'location', 'capacity', 'is_active', 'properties']);

        $campus = Campus::findOrFail($request->campus);

        $classroom = $this->classroomRepository->findOrFail($id);

        //dd($request->date_schudel);
        if (isset($request->all('schedule_id')['schedule_id'])) {
            event(new ClassroomUpdated($classroom, $classRoomSlotHelpers->slotsPicked()));
        }

        if (isset($request->week) && isset($request->new_schedule_id)) {
            //dd($request->new_schedule_id);
            event(new ClassroomCreated($classroom, $classRoomSlotHelpers->slotsPickedRepeater()));
        }
        /*if (isset($request->all('start_time')['start_time'])) {
            event(new ClassroomUpdated($classroom, $classRoomSlotHelpers->slotsPicked()));
        }

        if (isset($request->week) && isset($request->new_start_time) && isset($request->new_end_time)) {
            event(new ClassroomCreated($classroom, $classRoomSlotHelpers->slotsPickedRepeater()));
        }*/

        $this->classroomRepository->fill($classroom, $input);

        $classroom->campus()->associate($campus)->save();

        return redirect(route('classrooms.index'))
            ->with('success', "Classroom {$classroom->title} updated successfully!");
    }

    public function destroy($id)
    {
        $classroom = $this->classroomRepository->findOrFail($id);

        if ($response = $this->classroomRepository->delete($classroom)) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $classroom->id],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 404,
                    'response' => $response,
                ]
            );
        }
    }
}
