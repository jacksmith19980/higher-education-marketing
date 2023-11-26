<div style="display: flex; flex-wrap: wrap;" class="col-md-12 slot-row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.multi-select', [
            'name'          => 'week[' . $order . '][]',
            'label'         => 'Week day' ,
            'class'         => 'ajax-form-field',
            'required'      => true,
            'attr'          => '',
            'placeholder'   => 'Select a week day',
            'value'         => '',
            'data'          => [
                'Monday'        => 'Monday',
                'Tuesday'       => 'Tuesday',
                'Wednesday'     => 'Wednesday',
                'Thursday'      => 'Thursday',
                'Friday'        => 'Friday',
                'Saturday'      => 'Saturday',
                'Sunday'        => 'Sunday'
            ]
        ])
    </div>

    <div class="col-md-4" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create', ['classroom' => '1'])}}">
        <div class="form-group">
            @php
                if($schedules){
                    foreach($schedules as $k => $v){
                        $schedule[] = $v->id;

                        $fullSch = $v->label." (". substr($v->start_time,0,-3) . " - ". substr($v->end_time,0,-3) .")";
                        $allSchedule[] = $fullSch;

                    }
                    $data =  array_combine($schedule, $allSchedule);
                }else{
                    $schedule = isset($settings['calendar']['schedule_label']) ? $settings['calendar']['schedule_label'] : [];
                    $scheduleSTime = isset($settings['calendar']['schedule_start_time']) ? $settings['calendar']['schedule_start_time'] : [];
                    $scheduleETime = isset($settings['calendar']['schedule_end_time']) ? $settings['calendar']['schedule_end_time'] : [];
                    $allSchedule = [];
                    foreach($schedule as $k => $v){
                        $fullSch = $v." (". $scheduleSTime[$k] . " - ". $scheduleETime[$k].")";
                        array_push($allSchedule, $fullSch);
                    }
                    $data = array_combine($schedule, $allSchedule);
                }
                @endphp

                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'new_schedule_id[' . $order . ']',
                    'label'         => 'Schedule' ,
                    'class'         => 'date_schedule' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => isset($schedule_id) ? $schedule_id : '',
                    'data'          => $data,
                ])

        </div>
    </div>



    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'slot-row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</div>