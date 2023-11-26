<div class="col-md-12">
    <h5>Full Amount</h5>
</div>
<div class="col-md-12">
    <button type="button" class="close float-right" aria-label="Close" onclick="app.deletePaymentType('full-amount')">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.select',[
        'name' => 'properties[full_amount][due_date]',
        'label' => 'Due Date',
        'class' => 'select2',
        'required' => false,
        'attr' => '',
        'value' => (isset($application->properties['full_amount']['due_date']))? $application->properties['full_amount']['due_date'] : '',
        'data' => [
            'immediately' => 'Immediately',
            'before_start_date' => 'Before Start Date',
        ]
    ])
</div>
