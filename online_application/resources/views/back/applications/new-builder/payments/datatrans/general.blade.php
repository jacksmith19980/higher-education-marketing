<div class="row">

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[merchant_id]',
        'label' => 'Merchant ID' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => ( isset($payment->properties['merchant_id']) ) ? $payment->properties['merchant_id'] : ''
        ])
    </div>

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[merchant_sign]',
        'label' => 'Sign' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => ( isset($payment->properties['merchant_sign']) ) ? $payment->properties['merchant_sign'] : ''
        ])
    </div>

    <div class="col-md-12 mb-2">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[reposnse_url]',
        'label' => 'Response URL' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => 'readonly',
        'helper' => 'Use this link for Datatrans POST URL',
        'value' => route('payment.response' , ['school' => $school , 'gateway' => 'datatrans' ])
        ])
    </div>

    @include('back.applications.new-builder.payments._partials.general-settings')

</div>
