<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate="" enctype="multipart/form-data">
    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input',

                [

                    'name'          => 'group',

                    'value'         => 'stages',

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
                        @if(isset($settings['auth']['parent_login']) && $settings['auth']['parent_login'] == 'Yes')

                        <div class="col-md-12">

                            @include('back.layouts.core.forms.select',

                            [

                            'name' => 'new_parent_stage',

                            'label' => 'New parent\'s account stage' ,

                            'class' => 'select2',

                            'required' => true,

                            'attr' => '',

                            'value' => isset($settings['stages']['new_parent_stage'])? $settings['stages']['new_parent_stage'] : null,

                            'placeholder' => 'Select Stage',

                            'data' => $stages

                            ])

                        </div>

                        <div class="col-md-12">

                            @include('back.layouts.core.forms.select',

                            [

                            'name' => 'new_child_stage',

                            'label' => 'Parent added child stage' ,

                            'class' => 'select2',

                            'required' => true,

                            'attr' => '',

                            'value' => isset($settings['stages']['new_child_stage'])? $settings['stages']['new_child_stage'] : null,

                            'placeholder' => 'Select Stage',

                            'data' => $stages

                            ])

                        </div>

                        @else

                        <div class="col-md-12">

                            @include('back.layouts.core.forms.select',

                            [

                            'name' => 'new_account_stage',

                            'label' => 'New account\'s account stage' ,

                            'class' => 'select2',

                            'required' => true,

                            'attr' => '',

                            'value' => isset($settings['stages']['new_account_stage'])? $settings['stages']['new_account_stage'] : null,

                            'placeholder' => 'Select Stage',

                            'data' => $stages

                            ])

                        </div>

                        @endif

                        <div class="col-md-12">

                            @include('back.layouts.core.forms.select',

                            [

                            'name' => 'booking_email_stage',

                            'label' => 'Request booking by email' ,

                            'class' => 'select2',

                            'required' => true,

                            'attr' => '',

                            'value' => isset($settings['stages']['booking_email_stage'])? $settings['stages']['booking_email_stage'] : null,

                            'placeholder' => 'Select Stage',

                            'data' => $stages

                            ])

                        </div>

                        <div class="col-md-12">

                            @include('back.layouts.core.forms.select',

                            [

                            'name' => 'vaa_email_stage',

                            'label' => 'Receive VAA Summary by email' ,

                            'class' => 'select2',

                            'required' => true,

                            'attr' => '',

                            'value' => isset($settings['stages']['vaa_email_stage'])? $settings['stages']['vaa_email_stage'] : null,

                            'placeholder' => 'Select Stage',

                            'data' => $stages

                            ])

                        </div>

                        <div class="col-md-12">
                            @include('back.layouts.core.forms.select',

                            [

                            'name' => 'application_init_stage',

                            'label' => 'Application Initiated' ,

                            'class' => 'select2',

                            'required' => true,

                            'attr' => '',

                            'value' => isset($settings['stages']['application_init_stage'])? $settings['stages']['application_init_stage'] :
                            null,

                            'placeholder' => 'Select Stage',

                            'data' => $stages

                            ])

                        </div>
                        <div class="col-md-12">
                            @include('back.layouts.core.forms.select',

                            [

                            'name' => 'application_submitted_stage',

                            'label' => 'Application Submmited' ,

                            'class' => 'select2',

                            'required' => true,

                            'attr' => '',

                            'value' => isset($settings['stages']['application_submitted_stage'])?
                            $settings['stages']['application_submitted_stage'] : null,

                            'placeholder' => 'Select Stage',

                            'data' => $stages

                            ])

                        </div>

                        <div class="col-md-12">
                            @include('back.layouts.core.forms.select',
                            [
                            'name' => 'payment_completed',
                            'label' => 'Payment Completed' ,
                            'class' => 'select2',
                            'required' => true,
                            'attr' => '',
                            'value' => isset($settings['stages']['payment_completed'])? $settings['stages']['payment_completed'] : null,
                            'placeholder' => 'Select Stage',
                            'data' => $stages
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10">
            <button class="float-right btn btn-success">Save</button>
        </div>
    </div>
</form>
