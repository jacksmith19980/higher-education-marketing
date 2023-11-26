<div class="row">

        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'type',
            'label'     => '' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ucwords($integration_type)
        ])

         @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'application',
            'label'     => '' ,
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
            'value'     => isset($integration->title) ? $integration->title : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'method',
            'label'     => 'Method' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => [
                'post'  => 'POST',
                'get'   => 'GET'
            ],
            'value'     => isset($integration->data['method']) ? $integration->data['method'] : ''
        ])
    </div>


    <div class="col-md-12">
        @include('back.layouts.core.forms.multi-select',
        [
            'name'      => 'events',
            'label'     => 'Trigger Events' ,
            'class'     => 'select2 ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => [
            	'student_registered'			=> 'Student created new account',
            	'student_submitted_application'	=> 'Student submitted new application',
            	'student_updated_application'	=> 'Student updated application'
            ],
            'value'     => isset($integration->events) ? $integration->events : ''
        ])
    </div>


    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'url',
            'label'     => 'URL' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset($integration->data['url']) ? $integration->data['url'] : ''
        ])
    </div>

    
</div> <!-- row -->