<div class="row">
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

    <div class="col-md-12 new-field">
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

@include('back.applications.new-builder.payments._partials.general-settings')

</div> <!-- row -->
