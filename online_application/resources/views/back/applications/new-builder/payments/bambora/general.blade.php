<div class="row">
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input', [
            'name'      => 'properties[merchant_id]',
            'label'     => 'Merchant ID' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['merchant_id']) ) ? $payment->properties['merchant_id'] : ''
        ])

    </div>

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input', [
            'name'      => 'properties[passcode]',
            'label'     => 'Passcode' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['passcode']) ) ? $payment->properties['passcode'] : ''
        ])

    </div>

    @include('back.applications.new-builder.payments._partials.general-settings')

</div>
