<form method="POST" action="#" class="validation-wizard text_input_field">
    <div class="accordion-head bg-info text-white">Add New Educational Product</div>

    <div class="accordion-content accordion-active">
        <div class="row">
            <div class="col-md-6">
                @include('back.layouts.core.forms.select', [
                    'name'          => 'income_category',
                    'label'         => 'Income Category',
                    'class'         => 'ajax-form-field',
                    'required'      => true,
                    'attr'          => 'onchange=app.productsByCategory(this)',
                    'placeholder'   => 'Select a category',
                    'data'          => [
                        'Application'   => 'Application',
                        'Addon'         => 'Addon',
                        'Course'        => 'Course',
                        'Program'       => 'Program',
                    ],
                    'value'         => ''
                ])
            </div>

            <div class="col-md-6 products"></div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.text-input', [
                    'name'      => 'quantity',
                    'label'     => 'Quantity',
                    'class'     => 'ajax-form-field',
                    'required'  => true,
                    'attr'      => '',
                    'data'      => '',
                    'value'     => ''
                ])
            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.text-input', [
                    'name'      => 'amount',
                    'label'     => 'Amount',
                    'class'     => 'ajax-form-field',
                    'required'  => true,
                    'attr'      => '',
                    'data'      => '',
                    'value'     => ''
                ])
            </div>
        </div>
    </div>
</form>