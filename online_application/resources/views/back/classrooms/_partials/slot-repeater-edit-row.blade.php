@if(isset($keys) && count($keys) > 0)
    <div class="row options_group_transfer d-flex col-12" id="{{$day}}_options_wrapper">

        <div class="col-2">
            <h3>{{$day}}</h3>
        </div>
        <div class="col-10">
            @foreach ($keys as $key)
                @include('back.classrooms._partials.slot-row', [
                    'day' => $day,
                    'order' => $loop->index,
                    'schedule_id' => $classroom->classroomSlots->toArray()[$key]['schedule_id'],
                    {{-- 'start_time' => $classroom->classroomSlots->toArray()[$key]['start_time'], --}}
                    {{-- 'end_time'   => $classroom->classroomSlots->toArray()[$key]['end_time'], --}}
                    'id'         => $classroom->classroomSlots->toArray()[$key]['id']
                ])
            @endforeach
        </div>

        <div class="col-12">
            <hr>
        </div>
    </div>
@endif
