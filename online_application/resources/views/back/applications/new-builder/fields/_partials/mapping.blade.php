<div class="row">
<div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
    @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'custom_field',
        'label'     => 'Custom Field' ,
        'class'     => 'ajax-form-field' ,
        'required'  => false,
        'attr'      => 'readonly',
        'value'     => isset(optional($field)->custom_field) ? optional($field)->custom_field : null
    ])
</div>
</div>

<div class="row">

    <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'properties[map]',
                'label'     => 'Pre-populate data from' ,
                'class'     => 'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'data'      =>  ApplicationHelpers::getContactFieldMap(trim($application->object)) ,
                'value'     => isset(optional($field)->properties['map']) ? optional($field)->properties['map'] : null
            ])
    </div>

    <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[editable]',
                'label'         => 'Editable Field',
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Unlock field  (Can be edited)',
                'value'         => isset(optional($field)->properties['editable']) ? 1 : 0,
                'default'       => 1
            ])
    </div>
</div>
