<div style="display: flex; flex-wrap: wrap;" class="col-md-12 slot-row">

    <!-- <div class="col-md-5">
        <div class="form-group">
            @include('back.layouts.core.forms.time-input',
            [
                'name'      => 'start_time[' . $day . '][' . $order . ']',
                'label'     => 'Start Time' ,
                'class'     =>'' ,
                'required'  => true,
                'attr'      => '',
                'value'     => isset($start_time) ? substr($start_time, 0, 5)  : '',
                'data'      => ''
            ])
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            @include('back.layouts.core.forms.time-input',
            [
                'name'      => 'end_time[' . $day . '][' . $order . ']',
                'label'     => 'End Time' ,
                'class'     =>'' ,
                'required'  => true,
                'attr'      => '',
                'value'     => isset($end_time) ? substr($end_time, 0, 5) : '',
                'data'      => ''
            ])
        </div>
    </div> -->
    <div class="col-md-6" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create', ['course' => '1'])}}">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'schedule_id[' . $day . '][' . $order . ']',
                    'label'         => 'Schedule' ,
                    'class'         => 'date_schedule' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => isset($schedule_id) ? $schedule_id : '',
                    'data'          => SchoolHelper::getSchedulesList(),
                ])

    </div>

    @include('back.layouts.core.forms.hidden-input', [
        'name'     => 'updating[' . $day . '][' . $order . ']',
        'class'    =>'' ,
        'required' => false,
        'attr'     => '',
        'value'    => isset($id) ? $id : 0
    ])

    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'slot-row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</div>
