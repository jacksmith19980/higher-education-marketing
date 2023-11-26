@php
    if(!isset($campuses[0])){
        $campuses[0] = 'All Campuses';
        ksort($campuses);
    }
@endphp

<div class="fluid-container date-block">
<div>

    {{--  <div class="action_wrapper" style="position:absolute;top:-24px;right:6px;">
        <div class="form-group m-t-27">
            <button class="btn text-danger" style="background:none" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                <i class="fa fa-trash-alt"></i>
            </button>
        </div>
    </div>  --}}


    <div style="display: flex; flex-wrap: wrap;" class="row">
        <div class="col-md-4">
            @include('back.layouts.core.forms.date-input',
            [
                'name'          => "properties[$order][start_date]",
                'label'         => 'Start date' ,
                'class'         => '' ,
                'required'      => true,
                'attr'          => 'autocomplete=off',
                'value'         => isset($dateDetails[$order]['start_date']) ? $dateDetails[$order]['start_date'] : '',

                ])
        </div>

        <div class="col-md-4">
            @include('back.layouts.core.forms.date-input',
            [
                'name'          => "properties[$order][end_date]",
                'label'         => 'End date' ,
                'class'         => '' ,
                'required'      => true,
                'attr'          => 'autocomplete=off',
                'value'         => isset($dateDetails[$order]['end_date']) ? $dateDetails[$order]['end_date'] : ''
                ])
        </div>
        @features(['virtual_assistant', 'quote_builder', 'sis'])
            @php

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
                        'name'          => "properties[$order][date_schudel]",
                        'label'         => 'Schedule' ,
                        'class'         => 'date_schedule' ,
                        'required'      => true,
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
                        'name'          => "properties[$order][date_campus]",
                        'label'         => 'Campuses' ,
                        'class'         => '' ,
                        'required'      => true,
                        'attr'          => 'autocomplete=off',
                        'value'         => isset($dateDetails[$order]['date_campus']) ? $dateDetails[$order]['date_campus'] : '',
                        'data'          => $campuses,
                    ])
            </div>

            <div class="col-md-4">
                @include('back.layouts.core.forms.text-input',
                    [
                        'name'          => "properties[$order][date_price]",
                        'label'         => 'Price' ,
                        'class'         => '' ,
                        'required'      => false,
                        'attr'          => 'autocomplete=off',
                        'value'         => isset($dateDetails[$order]['date_price']) ? $dateDetails[$order]['date_price'] : ''
                    ])
            </div>



            <div class="col-md-4 d-flex justify-content-between">

                <div>
                    @include('back.layouts.core.forms.switch',
                    [
                        'name'          => "properties[$order][create_groups]",
                        'label'         => 'Create Cohort',
                        'helper_text'   => '',
                        'class'         => '',
                        'default'       => 1,
                        'required'      => false,
                        'attr'          => 'data-size=large',
                        'value'         => isset($dateDetails[$order]['create_groups']) ? $dateDetails[$order]['create_groups'] : 1,
                        'placeholder'   => '',
                    ])
                </div>
                @if($entityType == 'course')
                    <div>
                        @include('back.layouts.core.forms.switch',
                        [
                            'name'          => "create_lessons",
                            'label'         => 'Create Lessons',
                            'helper_text'   => '',
                            'class'         => 'create_lessons_switch',
                            'default'       => 1,
                            'required'      => false,
                            'attr'          => "data-size=large",
                            'value'         => isset($dateDetails[$order]['create_lessons']) ? $dateDetails[$order]['create_lessons'] : 0,
                            'placeholder'   => '',
                        ])
                    </div>
                @endif
            </div>

        </div>
        <div id="addLessons" class="fluid-container hidden">
            <div class="row">
                <div class="col-md-2 offset-10 mt-2 mb-2">
                    <button type="button" class="btn btn-success btn-block" onclick="app.addSlotElements(this)"
                    data-action="lesson.addLessonsToDate"
                    data-props='{{json_encode([
                        'dateType'     => $dateType,
                        'entity'       => $entity,
                        'entityType'   => $entityType
                    ])}}'
                    data-container=".lessons-slots">
                    <i class="fa fa-plus"></i> {{__('Add More ')}}
                    </button>
                </div>
            </div>
            <div class="lessons-slots fluid-container">
            </div>
        </div>
    @endfeatures
</div>

</div>
<script>
    window.addEventListener('switchChanged', (event) => {
        let details = event.detail;
        let props = {
            entity : {{$entity}},
        }
        details.props = props;

        app.addLessonsToDate(details);
    });
</script>
