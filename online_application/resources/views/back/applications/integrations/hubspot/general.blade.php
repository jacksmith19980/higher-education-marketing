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
        @include('back.layouts.core.forms.password', [
        'name' => 'api_key',
        'label' => 'API Token' ,
        'class' => 'ajax-form-field integration_data_field',
        'required' => true,
        'attr' => '',
        'value' => isset($integration->data['api_key']) ? $integration->data['api_key'] : '',
        'helper'=> 'Leave empty to keep the saved API Key'
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
