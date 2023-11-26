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
        'name'      => 'custom_field',
        'label'     => 'custom_field' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => 'program'
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'properties[wrapper_columns]',
        'label'     => 'Columns' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => 12
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'field_type',
        'label'     => 'field_type' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => $field_type ,
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
    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'object',
        'label'     => 'Filed Name' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => 'student'
    ])



    <div class="col-md-12 new-field">
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
    <div class="col-md-12 new-field">
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

    {{--  <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[enableMultiple]',
            'label'         => false ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Enable Multiple Selection',
            'value'         =>  isset(optional($field)->properties['enableMultiple']) ? 1 : 0,
            'default'       =>  1,
        ])
    </div>  --}}

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[showCampus]',
            'label'         => false ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Show Campus Filter',
            'value'         =>  isset(optional($field)->properties['showCampus']) ? 1 : 0,
            'default'       =>  1,
        ])
    </div>
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[showType]',
            'label'         => false ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Show Program Type Filter',
            'value'         =>  isset(optional($field)->properties['showType']) ? 1 : 0,
            'default'       =>  1,
        ])
    </div>

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[hideStartDate]',
            'label'         => false ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Hide Start Date Selection',
            'value'         =>  isset(optional($field)->properties['hideStartDate']) ? 1 : 0,
            'default'       =>  1,
        ])
    </div>

</div> <!-- row -->
