<div class="col-md-12">
    <h5>Installment with variable amount</h5>
</div>
<div class="col-md-12">
    <button type="button" class="close float-right" aria-label="Close" onclick="app.deletePaymentType('variable-amount')">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="col-md-12 mb-5">
    <button
            id="add_course"
            type="button"
            data-route={{route('course.new.form', $school)}}
                    onclick=app.addInstallment('variable-installment-wrapper')
            class="btn btn-success">Add Installment
    </button>
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select',[
        'name' => 'properties[variable_amount][first_payment][]',
        'label' => 'First Payment',
        'class' => 'select2',
        'required' => true,
        'attr' => '',
        'placeholder' => 'Select the first payment',
        'value' => (isset($application->properties['variable_amount']['first_payment']))? $application->properties['variable_amount']['first_payment'] : '',
        'data' => [
            'addons' => 'Addons',
            'fees' => 'Registration Fee',
        ]
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.select',[
        'name' => 'properties[variable_amount][due_date]',
        'label' => 'First Payment Due Date',
        'class' => 'select2',
        'required' => true,
        'attr' => '',
        'value' => (isset($application->properties['variable_amount']['due_date']))? $application->properties['variable_amount']['due_date'] : '',
        'data' => [
            'immediately' => 'Immediately',
            'before_start_date' => 'Before Start Date',
        ]
    ])
</div>

<div class="col-md-12 variable-installment-wrapper">
    @if(isset($application) && key_exists('amount_installments', $application->properties['variable_amount']))
        @foreach($application->properties['variable_amount']['amount_installments'] as $amount_installments)
            @include('back.applications._partials.application-creation.shared.installment-row', [
                    'amount_installments' => $amount_installments,
                    'date' => $application->properties['variable_amount']['date'][$loop->index],
                    'type' => $application->properties['variable_amount']['type'][$loop->index],
                    ])
        @endforeach
    @endif
</div>
