<div class="row">
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[api_key]',
            'label'     => 'Public API Key' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['api_key']) ) ? $payment->properties['api_key'] : ''
        ])
    </div>
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[secret_api_key]',
        'label' => 'Secret API Key' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => ( isset($payment->properties['secret_api_key']) ) ? $payment->properties['secret_api_key'] : ''
        ])
    </div>

    @include('back.applications.new-builder.payments._partials.general-settings')
</div> <!-- row -->
