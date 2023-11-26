
<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
    <div class="col-md-3">
        @include('back.layouts.core.forms.date-input',
    [
        'name'          => "properties[$order][start]",
        'label'         => 'Start date' ,
        'class'         => 'ajax-form-field' ,
        'required'      => true,
        'attr'          => 'autocomplete="off"',
        'date'          => '',
        'value'         => (isset($date->start)) ? date('Y-m-d' , strtotime($date->start)) : ''
        ])
    </div>

    <div class="col-md-3">
        @include('back.layouts.core.forms.date-input',
    [
        'name'          => "properties[$order][end]",
        'label'         => 'End date' ,
        'class'         => 'ajax-form-field' ,
        'required'      => true,
        'attr'          => '',
        'value'         => (isset($date->end)) ? date('Y-m-d' , strtotime($date->end)) : '',
        'data'          => ''
        ])
    </div>

    @php
        $schedules = App\Tenant\Models\Schedule::get();

        if($schedules->count()){

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
        'name'          => "properties[$order][schedule_id]",
        'label'         => 'Schedule' ,
        'class'         => 'bb ajax-form-field' ,
        'required'      => true,
        'attr'          => '',
        'value'         => (isset($date->schedule_id)) ? $date->schedule_id : '',
        'data'          => $data
    ])

    </div>

    <div class="col-md-2">
        @include('back.layouts.core.forms.switch',
        [
            'name'          => 'create_groups',
            'label'         => 'Create Cohort',
            'helper_text'   => '',
            'class'         => '',
            'default'       => 1,
            'required'      => false,
            'attr'          => 'data-size=larg',
            'value'         => 1,
            'placeholder'   => '',
        ])
    </div>

    {{--  <div class="col-md-2">
         @include('back.layouts.core.forms.text-input',
            [
                'name'          => "properties[$order][price]",
                'label'         => 'Price' ,
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'value'         => (isset($date->price)) ? $date->price : '',
                'data'          => '',
                'hint_after'    => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'
            ])
    </div>  --}}


    @if(!isset($date))
    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    @endif
</div>
