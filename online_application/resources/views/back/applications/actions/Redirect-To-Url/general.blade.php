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

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset($action->title) ? $action->title : ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'properties[url]',
            'label'         => 'Page URL' ,
            'class'         => 'ajax-form-field' ,
            'required'      => true,
            'placeholder'	=> 'http://',
            'attr'          => '',
            'value'         => isset($action->properties['url']) ? $action->properties['url'] : '',
        ])
    </div>
</div> <!-- row -->
