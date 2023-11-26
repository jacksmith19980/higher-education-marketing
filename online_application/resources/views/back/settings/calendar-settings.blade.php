<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
    enctype="multipart/form-data">
    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input', [
                    'name'          => 'group',
                    'value'         => 'calendar',
                    'class'         => '',
                    'required'      => '',
                    'attr'          => '',
        ])

        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Main')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'calendar_event_color',
                                'label' => 'Calendar event color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => '',
                                'value' => isset($settings['calendar']['calendar_event_color'])? $settings['calendar']['calendar_event_color'] : '',
                                'helper_text' => 'Used for calendar color events'
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'calendar_primary_color',
                                'label' => 'Calendar primary color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => '',
                                'value' => isset($settings['calendar']['calendar_primary_color'])? $settings['calendar']['calendar_primary_color'] : '',
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'calendar_secondary_color',
                                'label' => 'Calendar secondary color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => '',
                                'value' => isset($settings['calendar']['calendar_secondary_color'])? $settings['calendar']['calendar_secondary_color'] : '',
                            ])
                        </div>

                        <div class="col-md-12">
                                @include('back.layouts.core.forms.select', [
                                    'name'      => 'application',
                                    'label'     => 'Application related with calendar' ,
                                    'class'     => 'ajax-form-field' ,
                                    'required'  => true,
                                    'attr'      => '',
                                    'placeholder' => 'Select an Application',
                                    'data'      =>  ApplicationHelpers::getApplication(),
                                    'value'     => isset($settings['calendar']['application'])? $settings['calendar']['application'] : '',
                                ])
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @include('back.layouts.core.forms.text-input', [
                                'name' => '',
                                'label' => 'Calendar Link' ,
                                'class' => '',
                                'required' => false,
                                'attr' => 'readonly',
                                'value' => route('coursesCalendar.show', $school),
                            ])
                        </div>
                    </div>
                    <button class="float-right btn btn-success">Save</button>
                </div>
                <!-- <div class="col-md-10">
                    <button class="float-right btn btn-success">Save</button>
                </div> -->
            </div>
        </div>


    </div>
</form>
@include('back.settings.calendar.calendar-schedule')
