@php
    if(!isset($campuses[0])){
        $campuses[0] = 'All Campuses';
        ksort($campuses);
    }
@endphp

<div class="container date-row" style="border:1px solid #ccc;border-radius:10px;padding:20px;background-color:rgba(0, 0, 0, 0.1);margin-bottom:10px;position:relative">

    <div class="action_wrapper" style="position:absolute;top:-24px;right:6px;">
        <div class="form-group m-t-27">
            <button class="btn text-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </div>


    <div style="display: flex; flex-wrap: wrap;" class="row">
        <div class="col-md-4">
            @include('back.layouts.core.forms.date-input',
            [
                'name'          => "properties[start_date][$order]",
                'label'         => 'Start date' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => 'autocomplete=off',
                'value'         => isset($dateDetails[$order]['start_date']) ? $dateDetails[$order]['start_date'] : '',

                ])
        </div>

        <div class="col-md-4">
            @include('back.layouts.core.forms.date-input',
            [
                'name'          => "properties[end_date][$order]",
                'label'         => 'End date' ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => 'autocomplete=off',
                'value'         => isset($dateDetails[$order]['end_date']) ? $dateDetails[$order]['end_date'] : ''
                ])
        </div>
        @features(['virtual_assistant', 'quote_builder', 'sis'])
            @php
            $schedules = App\Tenant\Models\Schedule::get();

                if($schedules){
                    $schedule = [];
                    $allSchedule = [];
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

        <div class="col-md-4" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create', ['course' => '1'])}}">
            @include('back.layouts.core.forms.select',
                [
                        'name'          => "properties[date_schudel][$order]",
                        'label'         => 'Schedule' ,
                        'class'         => 'date_schedule' ,
                        'required'      => false,
                        'attr'          => 'autocomplete=off',
                        'value'         => isset($dateDetails[$order]['date_schudel']) ? $dateDetails[$order]['date_schudel'] : '',
                        'data'          => $data
                    ])
        </div>
        @endfeatures
    </div>

    @features(['virtual_assistant', 'quote_builder', 'sis'])
        <div  class="row">
            <div class="col-md-4">
                @include('back.layouts.core.forms.select',
                    [
                        'name'          => "properties[date_campus][$order]",
                        'label'         => 'Campuses' ,
                        'class'         => '' ,
                        'required'      => false,
                        'attr'          => 'autocomplete=off',
                        'value'         => isset($dateDetails[$order]['date_campus']) ? $dateDetails[$order]['date_campus'] : '',
                        'data'          => $campuses,
                    ])
            </div>
            <div class="col-md-4">

                @include('back.layouts.core.forms.text-input',
                    [
                        'name'          => "properties[date_price][$order]",
                        'label'         => 'Price' ,
                        'class'         => '' ,
                        'required'      => false,
                        'attr'          => 'autocomplete=off',
                        'value'         => isset($dateDetails[$order]['date_price']) ? $dateDetails[$order]['date_price'] : ''
                    ])
            </div>
        </div>
    @endfeatures
        {{--  <div class="col-md-1 action_wrapper">
            <div class="form-group m-t-27">
                <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>  --}}
</div>
