<div class="col-md-10">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('Welcome Email')}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'send_welcome_email',
                    'label' => 'Send Welcome Email After Registration',
                    'class' => '' ,
                    'required' => false,
                    'attr' => $disabled,
                    'data' => [
                        'Yes' => 'Yes',
                        'No' => 'No'
                    ],
                    'value' => isset($settings['auth']['send_welcome_email'])? $settings['auth']['send_welcome_email'] :
                    'No',
                    ])
                </div>
                </div>
                <div class="row">
                <div class="col-md-6">
                    @include('back.layouts.core.forms.text-input',
                        [
                            'name'      	=> 'welcome_email_from_name',
                            'label'     	=> 'Send From Name' ,
                            'class'     	=> '' ,
                            'placeholder'	=> '',
                            'helper'		=> '',
                            'required'  	=> false,
                            'attr'      	=> '',
                            'value'     	=> isset($settings['auth']['welcome_email_from_name'])? $settings['auth']['welcome_email_from_name']:'',
                        ])

                </div>

                <div class="col-md-6">
                    @include('back.layouts.core.forms.text-input',
                        [
                            'name'      	=> 'welcome_email_from_email',
                            'label'     	=> 'Send From Email' ,
                            'class'     	=> '' ,
                            'placeholder'	=> '',
                            'helper'		=> '',
                            'required'  	=> false,
                            'attr'      	=> '',
                            'value'     	=> isset($settings['auth']['welcome_email_from_email'])? $settings['auth']['welcome_email_from_email']:'',
                        ])

                </div>

                <div class="col-md-12">
                    @include('back.layouts.core.forms.text-input',
                        [
                            'name'      	=> 'welcome_email_subject',
                            'label'     	=> 'Subject' ,
                            'class'     	=> '' ,
                            'placeholder'	=> '',
                            'helper'		=> '',
                            'required'  	=> false,
                            'attr'      	=> '',
                            'value'     	=> isset($settings['auth']['welcome_email_subject'])? $settings['auth']['welcome_email_subject']:'',
                        ])

                </div>

                <div class="col-md-12">
                    @include('back.layouts.core.forms.html',
                    [
                    'name' => 'welcome_email',
                    'label' => 'Welcome Email' ,
                    'class' => '' ,
                    'required' => false,
                    'attr' => $disabled,
                    'value' => isset($settings['auth']['welcome_email']) ?
                    $settings['auth']['welcome_email'] : '',
                    'helper' => "%FIRST_NAME% = Student's first name, %LAST_NAME% = Student's last name, %EMAIL% = Student's email, %SCHOOL% = School Name"
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
