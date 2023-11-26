<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Dates',
            'action'        => 'course.addSpecificDates',
            'container'     => '#specific_dates_wrapper'
        ])
    </div>
</div>

<div class="row" id="specific_dates_wrapper">

    @if (isset($course->properties['start_date']) && !empty($course->properties['start_date']))

        @foreach ($course->properties['start_date'] as $key=>$startDate )
            <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

                <div class="col-md-3">
                    @include('back.layouts.core.forms.date-input',
                [
                    'name'          => 'properties[start_date][]',
                    'label'         => 'Start datess' ,
                    'class'         => '' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => isset($startDate) ? $startDate : '',
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
                    'value'         => isset($course->properties['end_date'][$key]) ? $course->properties['end_date'][$key] : '',
                    'data'          => ''
                    ])
                </div>

                @php
                       if($schedules){
                            foreach($schedules as $k => $v){
                                $schedule[] = $v->id;

                                $fullSch = $v->label." (". substr($v->start_time,0,-3) . " - ". substr($v->end_time,0,-3) .")";
                                $allSchedule[] = $fullSch;

                                if(isset($course->properties['date_schudel'][$key])){
                                    $value = $course->properties['date_schudel'][$key];
                                    if($value == $v->label){
                                        $value = $v->id;
                                 }
                                }
                            }
                            $data =  array_combine($schedule, $allSchedule);
                        }else{
                            $data = isset($settings['calendar']['schedule_label']) ? $settings['calendar']['schedule_label'] : [];
                            $value = $course->properties['date_schudel'][$key];
                        }
                    /*$schedule = isset($settings['calendar']['schedule_label']) ? $settings['calendar']['schedule_label'] : []*/
                @endphp
                <div class="col-md-3" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create' , $course)}}">
                    @include('back.layouts.core.forms.select',
                [
                    'name'          => 'properties[date_schudel][]',
                    'label'         => 'Schedule' ,
                    'class'         => 'date_schedule' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => $value {{--isset($course->properties['date_schudel'][$key]) ? $course->properties['date_schudel'][$key] : '' --}},
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
                    'attr'          => '',
                    'value'         => isset($course->properties['date_price'][$key]) ? $course->properties['date_price'][$key] : '',
                    'data'          => '',
                ])
                </div>

                <div class="col-md-1 action_wrapper">
                    <div class="form-group m-t-27">
                        <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

            </div>
        @endforeach
    @endif
</div>
