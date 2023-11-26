<div class="row">
    <div class="col-lg-2 offset-10 col-md-3 col-sm-4 col-xs-12 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Dates',
            'action'        => 'course.addAllYearCourses',
            'container'     => '#specific_dates_wrapper'
        ])
    </div>

</div>
<div class="row" id="specific_dates_wrapper">
     @if(isset($courseDates))
        @foreach($courseDates as $c)
            @if(isset($c['properties']['start_date']) && isset($c['properties']['end_date']) && !empty(isset($c['properties']['start_date'])) )
               {{-- @if((isset($c['properties']['start_date'])) >= date('Y-m-d')) --}}
                    <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row 2">

                        <input type='hidden' name="date_id[]" value="{{ $c['id']}}">
                        <div class="col-md-3">
                            @include('back.layouts.core.forms.select',
                        [
                            'name'          => 'properties[start_date][]',
                            'label'         => 'Start date' ,
                            'class'         => '' ,
                            'required'      => false,
                            'attr'          => '',
                            'value'         => isset($c['properties']['start_date']) ? $c['properties']['start_date'] : '',
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
                            'label'         => 'End date' ,
                            'class'         => '' ,
                            'required'      => false,
                            'attr'          => '',
                            'value'         => isset($c['properties']['end_date']) ? $c['properties']['end_date'] : '',
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
                            $value = '';
                             if($schedules){
                                foreach($schedules as $k => $v){
                                    $schedule[] = $v->id;

                                    $fullSch = $v->label." (". substr($v->start_time,0,-3) . " - ". substr($v->end_time,0,-3) .")";
                                    $allSchedule[] = $fullSch;

                                    if(isset($c['properties']['date_schudel'])){
                                        if($c['properties']['date_schudel'] == $v->label){
                                            $value = $v->id;
                                        }else{
                                            $value = $c['properties']['date_schudel'];
                                        }
                                    }



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
                                $value = $c['properties']['date_schudel'];
                            }

                            @endphp
                            <div class="col-md-3" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create' , $course)}}">

                                @include('back.layouts.core.forms.select',
                            [
                                'name'          => 'properties[date_schudel][]',
                                'label'         => 'Schedule' ,
                                'class'         => 'date_schedule' ,
                                'required'      => false,
                                'attr'          => '',
                                'value'         => $value {{-- isset($c['properties']['date_schudel']) ? $c['properties']['date_schudel'] : '' --}},
                                'data'          => $data,
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
                                'value'         => isset($c['properties']['date_price']) ? $c['properties']['date_price'] : '',
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
              {{--  @endif --}}
            @endif
        @endforeach

    @elseif(isset($course->properties['start_date']) && isset($course->properties['end_date']) && !empty($course->properties['start_date']))

        @foreach ($course->properties['start_date'] as $key=>$startDate )
            <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

                <div class="col-md-3">
                    @include('back.layouts.core.forms.select',
                [
                    'name'          => 'properties[start_date][]',
                    'label'         => 'Start date' ,
                    'class'         => '' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => isset($startDate) ? $startDate : '',
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
                @features(['virtual_assistant', 'quote_builder', 'sis'])
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
                                $schedule = isset($settings['calendar']['schedule_label']) ? $settings['calendar']['schedule_label'] : [];
                                $scheduleSTime = isset($settings['calendar']['schedule_start_time']) ? $settings['calendar']['schedule_start_time'] : [];
                                $scheduleETime = isset($settings['calendar']['schedule_end_time']) ? $settings['calendar']['schedule_end_time'] : [];
                                $allSchedule = [];
                                foreach($schedule as $k => $v){
                                    $fullSch = $v." (". $scheduleSTime[$k] . " - ". $scheduleETime[$k].")";
                                    array_push($allSchedule, $fullSch);
                                }
                                $value = $course->properties['date_schudel'][$key];
                                $data = array_combine($schedule, $allSchedule);
                            }

                    @endphp
                    <div class="col-md-3">

                        @include('back.layouts.core.forms.select',
                    [
                        'name'          => 'properties[date_schudel][]',
                        'label'         => 'Schedule' ,
                        'class'         => 'date_schedule' ,
                        'required'      => false,
                        'attr'          => 'onchange=app.showAddSchedule(this)',
                        'value'         => $value {{-- isset($course->properties['date_schudel'][$key]) ? $course->properties['date_schudel'][$key] : '' --}},
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
                        'value'         => $value {{--isset($course->properties['date_price'][$key]) ? $course->properties['date_price'][$key] : '' --}},
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
        @endforeach
    @endif
</div>
