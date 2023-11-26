<div class="row">

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[client_id]',
            'label'     => 'Client ID' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['client_id']) ) ? $payment->properties['client_id'] : ''

            ])
    </div>



    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[client_secret]',
            'label'     => 'Client Secret' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ( isset($payment->properties['client_secret']) ) ? $payment->properties['client_secret'] : ''
            ])

    </div>

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'properties[currency]',
            'label'     => 'Currency' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'placeholder'  => '',
            'attr'      => '',
            'data'      => [
                    'CAD' => 'Canadian dollar',
                    'EUR' => 'Euro',
                    'USD' => 'United States dollar',
            ],
            'value'     => ( isset($payment->properties['currency']) ) ? $payment->properties['currency'] : 'CAD'
        ])

    </div>

    @include('back.applications.new-builder.payments._partials.general-settings')

</div>
