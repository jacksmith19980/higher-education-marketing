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
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'section',
            'label'     => 'Section' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'placeholder'  => 'Don\'t add payment to the application',
            'attr'      => '',
            'data'      => ApplicationHelpers::getSectionsList($sections->toArray()),
            'value'     => ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[api_key]',
            'label'     => 'API Key' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['api_key']) ) ? $payment->properties['api_key'] : '',
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[store_id]',
            'label'     => 'Store ID' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['store_id']) ) ? $payment->properties['store_id'] : '',
        ])
    </div>
</div>
<div class="row">
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
</div>
<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[notification_emails]',
            'label'     => 'Payment Notification Emails' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'helper'    => 'Comma separated list of recepient emails',
            'value'     => ( isset($payment->properties['notification_emails']) ) ? $payment->properties['notification_emails'] : '',
        ])
    </div>
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'properties[payment_thank_you]',
            'label'         => 'Payment Thank You Page',
            'class'         =>'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'placeholder'   => 'https://',
            'value'         => ( isset($payment->properties['payment_thank_you']) ) ? $payment->properties['payment_thank_you'] : ''
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
