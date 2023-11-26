<div class="row">
    @include('back.layouts.core.forms.hidden-input',
    [
    'name' => 'type',
    'label' => '' ,
    'class' =>'ajax-form-field integration_data_field' ,
    'required' => true,
    'attr' => '',
    'value' => $integration_type
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
    'name' => 'application',
    'label' => '' ,
    'class' =>'ajax-form-field' ,
    'required' => true,
    'attr' => '',
    'value' => $application->id
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
        'name' => 'url',
        'label' => 'URL' ,
        'class' =>'ajax-form-field integration_data_field' ,
        'required' => true,
        'attr' => '',
        'value' => null
    ])

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'title',
        'label' => 'Title' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => isset($integration->title)? $integration->title : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
        'name' => 'url',
        'label' => 'Basic URL' ,
        'class' => 'ajax-form-field integration_data_field',
        'required' => true,
        'attr' => '',
        'value' => isset($integration->data['url']) ? $integration->data['url'] : ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
        'name' => 'ORGID',
        'label' => 'ORGID' ,
        'class' => 'ajax-form-field integration_data_field',
        'required' => true,
        'attr' => '',
        'value' => isset($integration->data['ORGID']) ? $integration->data['ORGID'] : ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
        'name' => 'MailListID',
        'label' => 'MailListID' ,
        'class' => 'ajax-form-field integration_data_field',
        'required' => true,
        'attr' => '',
        'value' => isset($integration->data['MailListID']) ? $integration->data['MailListID'] : ''
        ])
    </div>

    <div class="col-md-12">
        @include('back.layouts.core.forms.checkbox',
        [
        'name'          => 'sync_all_steps',
        'label'         => '',
        'class'         => 'ajax-form-field' ,
        'required'      => false,
        'attr'          => '',
        'helper_text'   => 'Push All steps to CampusLogin',
        'helper'        => 'If unchecked only the first and last steps will be pushed to CampusLogin',
        'default'       => 1,
        'value'         => isset($integration->data['sync_all_steps']) ? 1 : 0,
        ])
    </div>

    <div class="col-md-12">
        @include('back.layouts.core.forms.multi-select',
        [
        'name' => 'events',
        'label' => 'Trigger Events' ,
        'class' => 'select2 ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'data' => [
        'student_registered' => 'Student created new account',
        'student_submitted_application' => 'Student submitted new application',
        'student_updated_application' => 'Student updated application',
        'agency_application_submitted' => 'Agency Application Submitted'
        ],
        'value' => isset($integration->events) ? $integration->events : []
        ])
    </div>

</div> <!-- row -->
