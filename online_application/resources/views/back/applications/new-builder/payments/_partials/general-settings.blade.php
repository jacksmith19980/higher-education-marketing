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



{{--  <div class="col-md-12 new-field">

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
</div>  --}}
<div class="col-md-12 new-field">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'          => 'properties[is_sandbox_account]',
        'label'         => '' ,
        'class'         => 'ajax-form-field' ,
        'required'      => false,
        'attr'          => '',
        'helper_text'   => 'This is SandBox account',
        'value'         =>  ( isset($payment->properties['is_sandbox_account']) ) ? 1 : 0,
        'default'       =>  1,
    ])
</div>

{{--  <div class="col-md-12 new-field">
    @include('back.layouts.core.forms.checkbox',
    [
        'name'          => 'properties[disable_submission]',
        'label'         => '' ,
        'class'         => 'ajax-form-field' ,
        'required'      => false,
        'attr'          => '',
        'helper_text'   => 'Disable application submission if not paid',
        'value'         =>  ( isset($payment->properties['disable_submission']) ) ? $payment->properties['disable_submission'] : 0,
        'default'       =>  1,
    ])
</div>  --}}

<div class="col-md-12 new-field mb-2">
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

<div class="col-md-12 new-field">
    @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'properties[payment_thank_you]',
        'label'     => 'Payment Thank You Page URL',
        'class'     =>'ajax-form-field' ,
        'required'  => false,
        'attr'      => '',
        'value'     => ( isset($payment->properties['payment_thank_you']) ) ? $payment->properties['payment_thank_you'] : ''
        ])
</div>
<div class="col-md-12 new-field">
    @include('back.layouts.core.forms.html',
    [
        'name'          => 'properties[before_payment_text]',
        'label'         => 'Payment Instructions' ,
        'class'         => 'ajax-form-field' ,
        'required'      => false,
        'placeholder'   => '',
        'attr'          => '',
        'data'          => '',
        'value'         => ( isset($payment->properties['before_payment_text']) ) ? $payment->properties['before_payment_text'] : ''
    ])
</div>
