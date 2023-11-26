<form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
    @csrf

    <div class="accordion-head bg-info text-white">Create Invoice</div>

    <div class="accordion-content accordion-active">

        <div class="row">

            @include('back.layouts.core.forms.hidden-input',
                [
                    'name'      => 'student_id',
                    'label'     => '' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => false,
                    'attr'      => '',
                    'data'      => '',
                    'value'     => $student->id
                ])

            <div class="col-md-6">
                @include('back.layouts.core.forms.text-input',
                [
                    'name'      => 'total',
                    'label'     => 'Amount',
                    'class'     => 'ajax-form-field',
                    'required'  => true,
                    'attr'      => '',
                    'data'      => '',
                    'value'     => ''
                ])
            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'amount_category',
                    'label'     => 'Amount Category',
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'data'      => [
                        'tuition_fee' => 'Tuition Fee',
                        'registration_fee' => 'Registration Fee'
                    ],
                    'value'     => (isset($student->stage)) ? $student->stage : 'visa'
                ])

            </div>


                @include('back.layouts.core.forms.hidden-input',
                [
                    'name'      => 'payment_gateway',
                    'label'     => 'Payment Gateway',
                    'class'     => 'ajax-form-field',
                    'required'  => true,
                    'attr'      => '',
                    'data'      => '',
                    'value'     => '-'
                ])
{{--                \App\Helpers\Application\PaymentHelpers::getPaymentGateways()--}}


            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'program_id',
                    'label'     => 'Program',
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'data'      => \App\Helpers\Application\ProgramHelpers::getProgramInArrayOnlyTitleId(),
                    'value'     => (isset($student->stage)) ? $student->stage : 'visa'
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
