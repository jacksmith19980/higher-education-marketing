<div class="w-100"></div>
<div id="error-message"></div>
<div class="field_wrapper col-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'card_no',
        'label'     => __('Card Number') ,
        'class'     => '',
        'required'  => true,
        'attr'      => '',
        'value'     => ''
    ])
</div>

<div class="field_wrapper col-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'card_name',
        'label'     => __("Cardholder's name") ,
        'class'     => '',
        'required'  => true,
        'attr'      => '',
        'value'     => ''
    ])
</div>

<div class="field_wrapper col-4">
    @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'ccExpiryMonth',
        'label'     => __('Expiry Month (MM)') ,
        'class'     => '',
        'required'  => true,
        'attr'      => '',
        'placeholder' => 'MM',
        'value'     => ''
    ])
</div>

<div class="field_wrapper col-4">
    @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'ccExpiryYear',
        'label'     => __('Expiry Year (YYYY)'),
        'class'     => '',
        'required'  => true,
        'attr'      => '',
        'placeholder' => 'YYYY',
        'value'     => ''
    ])
</div>

<div class="field_wrapper col-4">
    @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'cvvNumber',
        'label'     => 'CVC' ,
        'class'     => '',
        'required'  => true,
        'attr'      => '',
        'placeholder' => '123',
        'value'     => ''
    ])
</div>

<input type='hidden' name='plugin_payment' value='{{$field->properties['plugin']}}'>
<input type='hidden' name='amount' value='{{$field->properties['amount']}}'>
<input type='hidden' name='category' value='{{$field->properties['category']}}'>
<input type='hidden' name='product' value='{{$field->properties['product']}}'>

<script type="text/javascript" src="//js.stripe.com/v3/"></script>
