<div class="row repeated_fields">

    <div class="col-md-2">
        @include('back.layouts.core.forms.select',[
            'name' => 'properties[variable_amount][type][]',
            'label' => 'Amount Type',
            'class' => 'select2',
            'required' => true,
            'attr' => '',
            'value' => (isset($type)) ? $type : '',
            'data' => [
                'percentage' => 'Percentage',
                'fixed' => 'Fixed',
            ]
        ])
    </div>

    <div class="col-md-5">
        @include('back.layouts.core.forms.text-input',[
            'name' => 'properties[variable_amount][amount_installments][]',
            'label' => 'Installment Amount' ,
            'class' => '' ,
            'value' => (isset($amount_installments)) ? $amount_installments : '',
            'required' => true,
            'attr' => '' ,
        ])
    </div>

    <div class="col-md-4">
        <div class="form-group">
            @include('back.layouts.core.forms.date-input',
            [
                'name'      => 'properties[variable_amount][date][]',
                'label'     => 'Due Date',
                'class'     =>'' ,
                'required'  => true,
                'attr'      => '',
                'value' => (isset($date)) ? $date : '',
                'data'      => ''
            ])
        </div>
    </div>

    <div class="col-md-1 action_wrapper">

        <label for="wert">Delete</label>

        <div class="form-group">
            <button class="btn btn-danger" type="button" onclick="app.removeInstallment(this)">
                <i class="fa fa-minus"></i>
            </button>
        </div>

    </div>
</div>
