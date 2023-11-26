<div class="row">
        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'type',
            'label'     => '' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $integration_type
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
            'value'     => isset($integration->title)? $integration->title : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'url',
            'label'     => 'Mautic URL' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset($integration->data['url']) ? $integration->data['url'] : ''
        ])
    </div>

   <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'username',
            'label'     => 'Mautic Username' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset($integration->data['username']) ? $integration->data['username'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'password',
            'label'     => 'Mautic Password' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset($integration->data['password']) ? $integration->data['password'] : ''
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
                'student_registered'            => 'Student created new account',
                'student_submitted_application' => 'Student submitted new application',
                'student_updated_application'   => 'Student updated application'
            ],
            'value'     => isset($integration->events) ? $integration->events : []
        ])
    </div>
    
</div> <!-- row -->