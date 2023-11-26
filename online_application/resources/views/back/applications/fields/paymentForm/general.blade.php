<div class="row">
    @include('back.layouts.core.forms.hidden-input',
    [
       'name'      => 'properties[type]',
       'label'     => 'Type' ,
       'class'     =>'ajax-form-field',
       'required'  => true,
       'attr'      => '',
       'value'     => $type
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'field_type',
        'label'     => 'Type',
        'class'     =>'ajax-form-field',
        'required'  => true,
        'attr'      => '',
        'value'     => $field_type
    ])

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title',
            'class'     =>'ajax-form-field',
            'required'  => true,
            'attr'      => 'onblur=app.constructFieldName(this)',
            'value'     => optional($field)->label
        ])
    </div>


    <div class="col-md-6">

        @php
            $disabled = (isset(optional($field)->name)) ? ' disabled' : ' ';
        @endphp

        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'name',
            'label'     => 'Filed Name' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => 'onkeyup=app.validateFieldName(this) ' . $disabled ,
            'value'     => optional($field)->name
        ])
    </div>



    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'object',
            'label'     => 'Object' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      =>  ['student' => 'Student' , 'parent' => 'Parent' , 'agent' => 'Agent'] ,
            'value'     =>  optional($field)->object
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'section',
            'label'     => 'Section' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => ApplicationHelpers::getSectionsList($sections->toArray()),
            'value'     => isset(optional($field)->section->id) ? optional($field)->section->id : ''
        ])
    </div>

    <div class="col-md-6">
        @php
            $plugins =\App\Helpers\School\PluginsHelper::getPlugins('payment')->toArray();
        @endphp
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'properties[plugin]',
            'label'     => 'Payment Plugin' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => array_combine(
                array_column($plugins, 'id'),
                array_map('ucfirst', array_column($plugins, 'name'))
                ),
            'value'     => isset(optional($field)->properties['plugin']) ? optional($field)->properties['plugin'] : null
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[amount]',
            'label'     => 'Amount' ,
            'class'     => 'ajax-form-field',
            'required'  => true,
            'attr'      => '' ,
            'value'     => isset(optional($field)->properties['amount']) ? optional($field)->properties['amount'] : null
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'          => 'properties[category]',
            'label'         => 'category',
            'class'         => 'ajax-form-field',
            'required'      => true,
            'attr'          => 'onchange=app.productsByCategory(this) data-order=0 data-field=' . 1,
            'placeholder'   => 'Select a category',
            'data'          => [
                'Application'   => 'Application',
                'Addon'         => 'Addon',
                'Course'        => 'Course',
                'Program'       => 'Program',
            ],
            'value'         => isset(optional($field)->properties['category']) ? $field->properties['category'] : ''
        ])
    </div>

    <div class="col-md-6 product-0">
        @if (isset(optional($field)->properties['amount']))
            @include('back.shared._partials.field_value', [
                'data'      => \App\Helpers\Invoice\InvoiceHelpers::getProductsBycategory($field->properties['category']),
                'name'      => 'properties[product]',
                'attr'      => 'onchange=app.amountByProduct(this) data-order=' . 0 . ' data-category=' . $field->properties['category'],
                'label'     => 'Educational Product',
                'required'  => true,
                'class'     => 'ajax-form-field',
                'value'     => $field->properties['product']
            ])
        @endif
    </div>

</div> <!-- row -->
