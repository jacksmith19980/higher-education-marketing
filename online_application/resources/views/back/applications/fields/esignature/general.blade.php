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

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'application',
        'label'     => 'Type' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => $sections->first()->applications->first()->id
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
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[disableSubmission]',
            'label'         => 'Application Submission' ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Disable application submission if not signed',
            'value'         =>  ( isset($field->properties['disableSubmission']) ) ? $field->properties['disableSubmission'] : 0,
            'default'       =>  1,
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'      => 'section',
            'label'     => 'Section' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'placeholder'  => 'Don\'t add sign to the application',
            'attr'      => '',
            'data'      => ApplicationHelpers::getSectionsList($sections->toArray()),
            'value'     => ( isset( $field->section_id ) ) ? $field->section_id : ''
        ])
    </div>


    <div class="col-md-6">
        @include('back.layouts.core.forms.checkbox', [
            'name'          => 'properties[enableSchoolSignature]',
            'label'         => 'School Signature' ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Enable School Signature',
            'value'         =>  ( isset($field->properties['enableSchoolSignature']) ) ? $field->properties['enableSchoolSignature'] : 0,
            'default'       =>  1,
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[schoolSignerName]',
            'label'     => 'School Signer\'s Name' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => ( isset($field->properties['schoolSignerName']) ) ? $field->properties['schoolSignerName'] : ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[schoolSignerEmail]',
            'label'     => 'School Signer\'s Email' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => ( isset($field->properties['schoolSignerEmail']) ) ? $field->properties['schoolSignerEmail'] : ''
        ])
    </div>

    <div class="col-md-12">
        @include('back.layouts.core.forms.html', [
            'name'      => 'properties[beforeSignText]',
            'label'     => 'Before Sign Text' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'data'      => '',
            'value'     => ( isset($field->properties['beforeSignText']) ) ? $field->properties['beforeSignText'] : ''
        ])
    </div>
</div>
