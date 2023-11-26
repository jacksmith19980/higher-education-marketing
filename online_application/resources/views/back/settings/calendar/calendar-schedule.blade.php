<form method="post" action="{{ route('settings.addNewSchedule') }}" class="needs-validation" novalidate=""
      enctype="multipart/form-data">
       @csrf
<div class="col-md-10" style="padding: 0;">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('Schedule')}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xlg-3 offset-xlg-9 col-lg-4 offset-lg-8 m-b-30 col-md-4 offset-md-8 col-sm-5 offset-sm-7 col-xs-6 offset-xs-6">
                    @include('back.layouts.core.helpers.add-elements-button' , [
                        'text'          => 'Add Schedule',
                        'action'        => 'coursesCalendar.addSchedule',
                        'container'     => '#specific_dates_wrapper'
                    ])
                </div>
            </div>

            <div class="row" id="specific_dates_wrapper">
                @if(isset($schedules))
                    @foreach($schedules as $key=>$schedule)
                        @if (isset($schedule->label) && isset($schedule->start_time) && isset($schedule->end_time))
                            <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
                                 @include('back.layouts.core.forms.hidden-input', [
                                    'name'          => 'schedule_id[]',
                                    'value'         => $schedule->id,
                                    'class'         => '',
                                    'required'      => '',
                                    'attr'          => '',
                                ])
                                <div class="col-md-5">
                                    @include('back.layouts.core.forms.text-input',
                                [
                                    'name'          => 'schedule_label[]',
                                    'label'         => 'Label' ,
                                    'class'         => '' ,
                                    'required'      => false,
                                    'attr'          => '',
                                    'value'         => $schedule->label,
                                    'data'          => ''
                                    ])
                                </div>

                                <div class="col-md-3">
                                    @include('back.layouts.core.forms.time-input',
                                    [
                                    'name' => 'schedule_start_time[]',
                                    'label' => 'Start time' ,
                                    'class' => 'ajax-form-field' ,
                                    'required' => true,
                                    'attr' => '',
                                    'value' => $schedule->start_time,
                                    'data' => ''
                                    ])

                                </div>

                                <!-- 'value' => $settings['calendar']['schedule_end_time'][$key], -->
                                <div class="col-md-3">
                                    @include('back.layouts.core.forms.time-input',
                                    [
                                    'name' => 'schedule_end_time[]',
                                    'label' => 'End time' ,
                                    'class' => 'ajax-form-field' ,
                                    'required' => true,
                                    'attr' => '',
                                    'value' => $schedule->end_time,
                                    'data' => ''
                                    ])
                                </div>

                                <div class="col-md-1 action_wrapper">
                                    <div class="form-group m-t-27">
                                       <!--  <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                                            <i class="fa fa-minus"></i>
                                        </button> -->
                                         <button class="btn btn-danger" id="{{ $schedule->id }}" delete-route="{{ route('settings.deleteSchedule', ['id'=> $schedule->id ]) }}" type="button" onclick="app.deleteSchedule(this, 'date-row')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        @endif
                    @endforeach

                @elseif (isset($settings['calendar']['schedule_label']) && isset($settings['calendar']['schedule_start_time']) && isset($settings['calendar']['schedule_end_time']))
                    @foreach ($settings['calendar']['schedule_label'] as $key => $schedule_label)
                        <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

                            <div class="col-md-5">
                                @include('back.layouts.core.forms.text-input',
                            [
                                'name'          => 'schedule_label[]',
                                'label'         => 'Label' ,
                                'class'         => '' ,
                                'required'      => false,
                                'attr'          => '',
                                'value'         => $schedule_label,
                                'data'          => ''
                                ])
                            </div>

                            <div class="col-md-3">
                                @include('back.layouts.core.forms.time-input',
                                [
                                'name' => 'schedule_start_time[]',
                                'label' => 'Start time' ,
                                'class' => 'ajax-form-field' ,
                                'required' => true,
                                'attr' => '',
                                'value' => $settings['calendar']['schedule_start_time'][$key],
                                'data' => ''
                                ])

                            </div>

                            <!-- 'value' => $settings['calendar']['schedule_end_time'][$key], -->
                            <div class="col-md-3">
                                @include('back.layouts.core.forms.time-input',
                                [
                                'name' => 'schedule_end_time[]',
                                'label' => 'End time' ,
                                'class' => 'ajax-form-field' ,
                                'required' => true,
                                'attr' => '',
                                'value' => $settings['calendar']['schedule_end_time'][$key],
                                'data' => ''
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
           <!--  <input type="submit" name="submit" value="Save"> -->
             <button class="float-right btn btn-success btn-add-schedule">Save</button>
        </div>
        <div class="col-md-10">

        </div>
    </div>
</div>
</form>
