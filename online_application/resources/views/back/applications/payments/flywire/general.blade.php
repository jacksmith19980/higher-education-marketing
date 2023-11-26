<div class="row">
        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'gateway',
            'label'     => 'Type' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $gateway
        ])

        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'application',
            'label'     => 'Type' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $sections->first()->applications->first()->id
        ])


    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[destination]',
            'label'     => 'Destination (Unique Code)' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => (isset( $payment->properties['destination'] )) ? $payment->properties['destination'] : ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'section',
            'label'     => 'Section' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'placeholder'  => 'Don\'t add payment to the application',
            'data'      => ApplicationHelpers::getSectionsList($sections->toArray()),
            'value'     => ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.checkbox',
        [
            'name'            => 'properties[is_sandbox_account]',
            'label'         => 'Sandbox account' ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'This is SandBox account',
            'value'         =>  ( isset($payment->properties['is_sandbox_account']) ) ? $payment->properties['is_sandbox_account'] : 0,
            'default'       =>  1,
        ])
    </div> 

    <div class="col-md-6">
        @include('back.layouts.core.forms.checkbox',
        [
            'name'            => 'properties[disable_submission]',
            'label'         => 'Application Submission' ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Disable application submission if not paid',
            'value'         =>  ( isset($payment->properties['disable_submission']) ) ? $payment->properties['disable_submission'] : 0,
            'default'       =>  1,
        ])
    </div> 


    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[payment_thank_you]',
            'label'     => 'Payment Thank You',
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => ( isset($payment->properties['payment_thank_you']) ) ? $payment->properties['payment_thank_you'] : ''
            ])
    </div>
    <div class="col-md-12">
        @include('back.layouts.core.forms.html',
        [
            'name'      => 'properties[before_payment_text]',
            'label'     => 'Before Payment Text' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'placeholder'  => 'Don\'t add payment to the application',
            'attr'      => '',
            'data'      => '',
            'value'     => ( isset($payment->properties['before_payment_text']) ) ? $payment->properties['before_payment_text'] : ''
        ])
    </div>

</div> <!-- row -->