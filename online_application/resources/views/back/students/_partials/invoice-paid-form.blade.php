<form method="POST" action="{{ route($route , $invoice ) }}" class="validation-wizard text_input_field">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div class="accordion-head bg-info text-white">Paid invoice</div>

    <div class="accordion-content accordion-active">

        <div class="row">

            <div class="col-md-6">
                @include('back.layouts.core.forms.text-input',
                [
                    'name'      => 'Amount',
                    'label'     => 'Amount' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'data'      => '',
                    'value'     => ''
                ])
            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'Payment Method',
                    'label'     => 'Payment Method' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => false,
                    'attr'      => '',
                    'data'      => [
                            ''          => 'Select',
                            'visa'      => 'Credit Card',
                            'interact' 	=> 'Interact',
                            'wire' 	    => 'Wire',
                            'cheque' 	=> 'Cheque',
                    ],
                    'value'     => ''
                ])

            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'Card Brand',
                    'label'     => 'Card Brand' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => false,
                    'attr'      => '',
                    'data'      => [
                        ''              => 'Select',
                        'visa'          => 'Visa',
                        'master_card' 	=> 'Master Card',
                    ],
                    'value'     => ''
                ])

            </div>

            <div class="col-md-3">
                @include('back.layouts.core.forms.date-input',
                [
                'name' => 'Created',
                'label' => 'Created at' ,
                'class' => 'ajax-form-field' ,
                'required' => true,
                'attr' => '',
                'value' => '',
                'data' => ''
                ])

            </div>
        </div>
    </div>

</form>
