<div class="row">
    <div class="col-md-12">
        <div class="card card-border">
            <div class="card-header">
                <h4 class="card-title">{{__('Course Date')}}</h4>
            </div>
            <div class="card-body">

                <div class="row">

                    @include('back.layouts.core.forms.hidden-input',
                    [
                        'name' => 'date_type',
                        'label' => '' ,
                        'class' => 'ajax-form-field' ,
                        'required' => true,
                        'attr' => '',
                        'value' => $course->properties['dates_type'],
                    ])

                    @include('back.layouts.core.forms.hidden-input',
                    [
                        'name' => 'key',
                        'label' => '' ,
                        'class' => 'ajax-form-field' ,
                        'required' => true,
                        'attr' => '',
                        'value' => $key,
                    ])

                    <div class="col-md-6">
                        @include('back.layouts.core.forms.date-input',
                        [
                        'name'  => 'properties[start_date]',
                        'label' => 'Start date' ,
                        'class' => 'ajax-form-field' ,
                        'required' => true,
                        'attr' => '',
                        'value' => isset($date['properties']['start_date']) ? $date['properties']['start_date']  : '',
                        'data' => ''
                        ])
                    </div>

                    <div class="col-md-6">
                        @include('back.layouts.core.forms.date-input',
                        [
                        'name' => 'properties[end_date]',
                        'label' => 'End date' ,
                        'class' => 'ajax-form-field' ,
                        'required' => true,
                        'attr' => '',
                        'value' => isset($date['properties']['end_date']) ? $date['properties']['end_date'] : '',
                        'data' => ''
                        ])

                    </div>

                    <div class="col-md-6">
                        @include('back.layouts.core.forms.text-input',
                        [
                        'name' => 'properties[date_price]',
                        'label' => 'Price' ,
                        'class' => 'ajax-form-field' ,
                        'required' => true,
                        'attr' => '',
                        'value' => isset($date['properties']['date_price']) ? $date['properties']['date_price'] : '',
                        'data' => '',
                        'hint_after' => isset($settings['school']['default_currency']) ?
                        $settings['school']['default_currency'] : 'CAD',
                        ])
                    </div>

                    @php
                        /*$schedule = isset($settings['calendar']['schedule_label']) ? $settings['calendar']['schedule_label'] : [] */
                        if($schedules){
                            foreach($schedules as $k => $v){
                                $schedule[] = $v->id;
                                $fullSch = $v->label." (". substr($v->start_time,0,-3) . " - ". substr($v->end_time,0,-3) .")";
                                $allSchedule[] = $fullSch;
                            }
                            $data =  array_combine($schedule, $allSchedule);
                        }else{
                            $data = isset($settings['calendar']['schedule_label']) ? $settings['calendar']['schedule_label'] : [];
                        }
                    @endphp

                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select',
                        [

                            'name'      => 'properties[date_schudel]',
                            'label'     => 'Schedule' ,
                            'class'     =>'ajax-form-field' ,
                            'required'  => true,
                            'attr'      => '',
                            'value'     => isset($date['properties']['date_schudel']) ? $date['properties']['date_schudel'] : '',
                            'data'      => $data
                        ])
                      {{--  @include('back.layouts.core.forms.select',  --}}
                      {{--  [ --}}

                     {{--       'name'      => 'properties[scheduled]',--}}
                     {{--       'label'     => 'Scheduled' ,--}}
                     {{--       'class'     =>'ajax-form-field' ,--}}
                     {{--       'required'  => true,--}}
                     {{--       'attr'      => '',--}}
                     {{--       'value'     => isset($date['properties']['scheduled']) ? $date['properties']['scheduled'] : '',--}}
                      {{--      'data'      => array_combine($schedule, $schedule)--}}
                      {{--  ]) --}}
                    </div>
                </div>

                <div class="card-inner-body">
                    <div class="card-header">
                        <div class="d-flex no-block align-items-center justify-content-center">
                            <h4 class="card-title d-block">{{__("Addons")}}</h4>
                            <div class="ml-auto">
                                <span class="btn btn-light"
                                    data-payload="key={{$key}}&template=specific-dates"
                                    data-action="course.getDateAddonsTemplate"
                                    data-container=".course_date_addon_repeater"
                                    onclick="app.duplicate(this, '.course_date_addon')">

                                    <i class="icon-plus"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="course_date_addon_repeater"></div>

                    @if (isset($date['properties']['addons']))
                        @foreach ($date['properties']['addons'] as $addon)
                            @include('back.courses._partials.dates.dates-template.'.$course->properties['dates_type'].'.addons' , ['key' , $key , 'addon' => $addon])

                        @endforeach
                    <!--
                    @ else
                        @ include('back.courses._partials.dates.dates-template.'.$course->properties['dates_type'].'.addons' , ['key' , $key ])
                    -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="course_dates_repeater"></div>
    </div>
</div>
