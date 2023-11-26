<div class="row">

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'properties[type]',
        'label'     => 'Type' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => $type
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'field_type',
        'label'     => 'Type' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => $field_type
    ])


    @php
        $disabled = (isset(optional($field)->name)) ? ' disabled' : ' ';
    @endphp

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'name',
        'label'     => 'Filed Name' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => 'onkeyup=app.validateFieldName(this) ' . $disabled ,
        'value'     => optional($field)->name
    ])

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => 'onblur=app.constructFieldName(this)',
            'value'     => optional($field)->label
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

    <div class="col-md-12 mt-1">&nbsp;</div>

    <div class="col-md-4">
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[showCampus]',
            'label'         => 'Show Campus Selection' ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Filter by Campus',
            'value'         =>  isset(optional($field)->properties['showCampus']) ? $field->properties['showCampus'] : 0,
            'default'       =>  1,
        ])
    </div>
    <div class="col-md-4">
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[showType]',
            'label'         => 'Show Program Type Selection' ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Filter by Program Type',
            'value'         =>  isset(optional($field)->properties['showType']) ? $field->properties['showType'] : 0,
            'default'       =>  1,
        ])
    </div>

    <div class="col-md-4">
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[hideStartDate]',
            'label'         => 'Hide Start Date Selection' ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Hide Start Date Selection',
            'value'         =>  isset(optional($field)->properties['hideStartDate']) ? $field->properties['hideStartDate'] : 0,
            'default'       =>  1,
        ])
    </div>

</div> <!-- row -->
