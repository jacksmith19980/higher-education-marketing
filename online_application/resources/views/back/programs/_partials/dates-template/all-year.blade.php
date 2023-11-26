<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
    <div class="col-md-3">
        @include('back.layouts.core.forms.select',
    [
        'name'          => 'properties[start_date][]',
        'label'         => 'From' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => 'autocomplete=off',
        'value'         => '',
        'data'          => [
                "Monday"    => "Monday",
                "Tuesday"   => "Tuesday",
                "Wednesday" => "Wednesday",
                "Thursday"  => "Thursday",
                "Friday"    => "Friday",
                "Saturday"  => "Saturday",
                "Sunday"    => "Sunday",
            ]
        ])
    </div>

    <div class="col-md-3">
        @include('back.layouts.core.forms.select',
    [
        'name'          => 'properties[end_date][]',
        'label'         => 'To' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => 'autocomplete=off',
        'value'         => '',
        'data'          => [
                "Monday"    => "Monday",
                "Tuesday"   => "Tuesday",
                "Wednesday" => "Wednesday",
                "Thursday"  => "Thursday",
                "Friday"    => "Friday",
                "Saturday"  => "Saturday",
                "Sunday"    => "Sunday",
            ]
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

    <div class="col-md-3" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create', ['course' => '1'])}}">
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
    </div>

    <div class="col-md-2">
        @include('back.layouts.core.forms.text-input',
    [
        'name'          => 'properties[date_price][]',
        'label'         => 'Price' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => 'autocomplete=off',
        'value'         => '',
        'data'          => '',
    ])
    </div>
   @nofeatures
   @endfeatures

    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</div>
