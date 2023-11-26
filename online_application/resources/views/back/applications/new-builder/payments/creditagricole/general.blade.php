<div class="row">
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[pbx_site]',
        'label' => 'PBX Site' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => ( isset($payment->properties['pbx_site']) ) ? $payment->properties['pbx_site'] : ''
        ])
    </div>
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[pbx_rang]',
        'label' => 'PBX Rang' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => ( isset($payment->properties['pbx_rang']) ) ? $payment->properties['pbx_rang'] : ''
        ])
    </div>


    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[pbx_identifiant]',
        'label' => 'PBX Identifiant' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => ( isset($payment->properties['pbx_identifiant']) ) ? $payment->properties['pbx_identifiant'] : ''
        ])
    </div>
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[key]',
        'label' => 'Key' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => ( isset($payment->properties['key']) ) ? $payment->properties['key'] : ''
        ])
    </div>
    <div class="col-md-12 mb-2">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[pbx_repondre_a]',
        'label' => 'Response URL' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => 'readonly',
        'helper' => 'Use this link for Crédit Agricole POST URL',
        'value' => route('payment.response' , ['school' => $school , 'gateway' => 'creditagricole' ])
        ])
    </div>

    @include('back.applications.new-builder.payments._partials.general-settings')

</div>
