<div class="row">

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[api_login_id]',
            'label'     => 'API Login ID' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['api_login_id']) ) ? $payment->properties['api_login_id'] : '',
        ])
    </div>

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[transaction_key]',
            'label'     => 'Transaction Key' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['transaction_key']) ) ? $payment->properties['transaction_key'] : '',
        ])
    </div>

    <div class="col-md-12 new-field">
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

    @include('back.applications.new-builder.payments._partials.general-settings')

</div> <!-- row -->
