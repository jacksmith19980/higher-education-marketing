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

        <div class="col-md-12 new-field">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'title',
                'label'     => 'Title' ,
                'class'     =>'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'value'     => isset($action->title) ? $action->title : __('Send Thank You Email') ,
            ])
        </div>

        <div class="col-md-12 new-field">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[one_time_action]',
                'label'         => '',
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Run action one time only',
                'value'         => isset($action->properties['one_time_action']) ? 1 : 0,
                'default'       => 1
            ])
        </div>


        <div class="col-md-12 new-field">
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

        <div class="col-md-12 new-field">
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
<script type="text/javascript">
$(document).ready(function(){
    $(".select2").select2();
});
</script>
