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
            'label'     => '' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $application->id
        ])

        <div class="col-md-12">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'title',
                'label'     => 'Title' ,
                'class'     =>'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'value'     => isset($action->title) ? $action->title : '' ,
            ])
        </div>

        <div class="col-md-4">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[one_time_action]',
                'label'         => 'Run action one time only',
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Yes',
                'value'         => isset($action->properties['one_time_action']) ? $action->properties['one_time_action'] : 0,
                'default'       => 1
            ])
        </div>


        <div class="col-md-8">
            @include('back.layouts.core.forms.multi-select',
            [
                'name'      => 'properties[send_to]',
                'label'     => 'Send Email To' ,
                'class'     => 'select2 ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'data'      => [
                    'student'   => 'Student',
                    'parent'    => 'Parent',
                    'agent'     => 'Agent'
                ],
                'value'     => isset($action->properties['send_to']) ? $action->properties['send_to'] : []
            ])
        </div>

        <div class="col-md-12">
            @include('back.layouts.core.forms.html',
                [
                    'name'      => 'properties[email]',
                    'label'     => 'Email',
                    'class'     => 'ajax-form-field',
                    'value'     => isset($action->properties['email']) ? $action->properties['email'] : '',
                    'required'  => false,
                    'attr'      => '',
                ])
        </div>
</div><!-- row -->
