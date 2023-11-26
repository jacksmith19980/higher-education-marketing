<div class="row">
    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'type',
        'label'     => '' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => $actionName
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'application',
        'label'     => 'Type' ,
        'class'     =>'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => $application->id
    ])

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset($action->title) ? $action->title: '',
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
            'value'         =>  ( isset($action->properties['enableSchoolSignature']) ) ? $action->properties['enableSchoolSignature'] : 0,
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
            'value'     => ( isset($action->properties['schoolSignerName']) ) ? $action->properties['schoolSignerName'] : ''
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
            'value'     => ( isset($action->properties['schoolSignerEmail']) ) ? $action->properties['schoolSignerEmail'] : ''
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
            'value'     => ( isset($action->properties['beforeSignText']) ) ? $action->properties['beforeSignText'] : ''
        ])
    </div>
</div>
