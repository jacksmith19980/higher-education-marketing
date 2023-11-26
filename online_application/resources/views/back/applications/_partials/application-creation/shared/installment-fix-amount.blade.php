<div class="col-md-12">
    <h5>Installment with fixed amount</h5>
</div>
<div class="col-md-12">
    <button type="button" class="close float-right" aria-label="Close" onclick="app.deletePaymentType('fixed-amount')">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select',[
        'name' => 'properties[fixed_amount][first_payment][]',
        'label' => 'First Payment',
        'class' => 'select2',
        'required' => true,
        'attr' => '',
        'placeholder' => 'Select the first payment',
        'value' => (isset($application->properties['fixed_amount']['first_payment']))? $application->properties['fixed_amount']['first_payment'] : '',
        'data' => [
            'addons' => 'Addons',
            'fees' => 'Registration Fee',
        ]
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.select',[
        'name' => 'properties[fixed_amount][due_date]',
        'label' => 'First Payment Due Date',
        'class' => 'select2',
        'required' => true,
        'attr' => '',
        'value' => (isset($application->properties['fixed_amount']['due_date']))? $application->properties['fixed_amount']['due_date'] : '',
        'data' => [
            'immediately' => 'Immediately',
            'before_start_date' => 'Before Start Date',
        ]
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',[
        'name' => 'properties[fixed_amount][amount_installments]',
        'label' => 'How many installments' ,
        'class' => '' ,
        'value' => (isset($application->properties['fixed_amount']['amount_installments']))? $application->properties['fixed_amount']['amount_installments'] : '',
        'required' => true,
        'attr' => '' ,
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.select',[
        'name' => 'properties[fixed_amount][frequency]',
        'label' => 'Frequency',
        'class' => 'select2',
        'required' => false,
        'attr' => '',
        'value' => (isset($application->properties['fixed_amount']['frequency']))? $application->properties['fixed_amount']['frequency'] : '',
        'data' => [
            'montly' => 'Montly',
            'three_month' => 'Every 3 months',
        ]
    ])
</div>
