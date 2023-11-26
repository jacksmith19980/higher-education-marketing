<div class="tab-pane fade @if($day == 'Monday')  active show @endif" id="{{$day}}" role="tabpanel" aria-labelledby="pills-{{$day}}-tab">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">

                <hr>
                <h4 class="m-t-20">{{__($day)}}</h4>
                <div class="row options_group_transfer">
                    <div class="col-md-3 offset-9 m-b-20">
                        @include('back.classrooms._partials.add-slot-button' , [
                            'action' => 'classroomSlot.create',
                            'container' => '#' . $day . '_options_wrapper',
                            'text' => 'Add Slot to ' . $day
                        ])
                    </div>
                </div>

                <div class="row options_group_transfer d-flex" id="{{$day}}_options_wrapper">
                    @if(isset($keys) && count($keys) > 0)
                        @foreach ($keys as $key)
                            @include('back.classrooms._partials.slot-row', [
                                'day' => $day,
                                'schedule_id' => $classroom->classroomSlots->toArray()[$key]['schedule_id'],
                                {{-- 'start_time' => $classroom->classroomSlots->toArray()[$key]['start_time'], --}}
                                {{-- 'end_time'   => $classroom->classroomSlots->toArray()[$key]['end_time'], --}}
                                'id'         => $classroom->classroomSlots->toArray()[$key]['id']
                            ])
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>