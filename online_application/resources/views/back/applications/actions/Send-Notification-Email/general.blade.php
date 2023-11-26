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
    
    <div class="row">
        
        <div class="col-md-12">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'title',
                'label'     => 'Title' ,
                'class'     =>'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'value'     => (isset($action)) ? $action->title : ''
            ])
        </div>
   
        <div class="col-md-12">
                @include('back.layouts.core.forms.text-input',
                [
                    'name'      	=> 'properties[send_to]',
                    'label'     	=> 'Recipients' ,
                    'class'     	=>'ajax-form-field' ,
                    'value'     	=> isset($action->properties['send_to']) ? $action->properties['send_to'] : '',
                    'placeholder'	=> 'Comma separated list of emails',
                    'helper'		=> 'Comma separated list of emails',
                    'required'  	=> true,
                    'attr'      	=> '' 
                ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[attach_pdf]',
                'label'         => '' ,
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Attach PDF Copy of the application',
                'value'         =>  isset($action->properties['attach_pdf']) ? $action->properties['attach_pdf'] : '',
                'default'       => true,
            ])
        </div>

        <div class="col-md-12">
                @include('back.layouts.core.forms.html',
            [
                'name'      => 'properties[email]',
                'label'     => 'Email',
                'class'     => 'ajax-form-field',
                'required'  => true,
                'value'     => isset($action->properties['email']) ? $action->properties['email'] : '',
                'attr'      => '',
            ])
        </div>
</div><!-- row -->