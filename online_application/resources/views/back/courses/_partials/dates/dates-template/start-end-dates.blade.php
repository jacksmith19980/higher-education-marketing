
<div class="col-md-3">
        @include('back.layouts.core.forms.date-input',
    [
        'name'          => 'properties[start_date][]',
        'label'         => 'Start date' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => '',
        'value'         => '',
        'data'          => ''
        ])
    </div>

    <div class="col-md-3">
        @include('back.layouts.core.forms.date-input',
    [
        'name'          => 'properties[end_date][]',
        'label'         => 'End date' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => '',
        'value'         => '',
        'data'          => ''
        ])
    </div>

    @php
        $schedules = App\Tenant\Models\Schedule::get();

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

    <div class="col-md-3" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create' , $course)}}">
          @include('back.layouts.core.forms.select',
    [
        'name'          => 'properties[date_schudel][]',
        'label'         => 'Schedule' ,
        'class'         => 'date_schedule' ,
        'required'      => false,
        'attr'          => 'autocomplete=off',
        'value'         => '',
        'data'          => $data
    ])
{{--        @include('back.layouts.core.forms.select',  --}}
{{--    [ --}}
{{--        'name'          => 'properties[date_schudel][]',--}}
{{--        'label'         => 'Schudel' ,--}}
{{--        'class'         => '' ,--}}
{{--        'required'      => false,--}}
{{--        'attr'          => '',--}}
{{--        'value'         => '',--}}
{{--        'data'          => [--}}
{{--            'AM'    => 'AM',--}}
{{--            'AM1'   => 'AM1',--}}
{{--            'AM2'   => 'AM2',--}}
{{--            'EVE'   => 'EVE',--}}
{{--        ],--}}
{{--    ])--}}
</div>

    <div class="col-md-2">
            @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'properties[date_price][]',
            'label'         => 'Price' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => '',
            'data'          => '',
        ])
    </div>



    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
                        <button class="btn btn-danger" type="button" onclick="">
                            <i class="fa fa-minus"></i>
                        </button>
        </div>
    </div>
