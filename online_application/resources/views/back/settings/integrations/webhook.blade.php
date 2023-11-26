<div class="col-md-10">
<div class="card" style="width: 100% disabled" disabled>
    <div class="text-white card-header bg-info" role="tab" id="headingOne">

        <h5 data-toggle="collapse" data-parent="#accordion_application_action" href="#integration_webhook" aria-expanded="true" aria-controls="collapseOne" class="float-left mb-0">{{__('Webhook')}}</h5>
        <div class="float-right">
                @include('back.layouts.core.forms.switch',
                    [
                        'name'          => 'integration_webhook',
                        'label'         => '',
                        'class'         => 'switch' ,
                        'required'      => false,
                        'attr'          => 'data-size=mini data-on-text=Enabled data-off-text=Disabled onchange=app.toggleSchoolIntegration(this) data-integration=integration_webhook',
                        'helper_text'   => '',
                        'value'         => isset($settings['integrations']['integration_webhook'])? $settings['integrations']['integration_webhook'] : '',
                        'default'       => 1
                    ])
        </div>

    </div>

    <div id="integration_webhook" class="collapse show" role="tabpanel" aria-labelledby="headingOne">

        <div class="card-body">
            @include('back.layouts.core.forms.hidden-input',
                [
                    'name'      	=> 'integrations[]',
                    'label'     	=> '' ,
                    'class'     	=> '' ,
                    'value'     	=> 'webhook',
                    'placeholder'	=> '',
                    'helper'		=> '',
                    'required'  	=> true,
                    'attr'      	=> 'disabled'
                ])
        <div class="row">
                <div class="col-md-8">
                        @include('back.layouts.core.forms.text-input',
                        [
                            'name'      => 'webhook_url',
                            'label'     => 'URL' ,
                            'class'     => '' ,
                            'required'  => true,
                            'attr'      => 'disabled',
                            'value'     => isset($settings['integrations']['webhook_url'])? $settings['integrations']['webhook_url'] : ''
                        ])
                </div>
                <div class="col-md-2">
                    @include('back.layouts.core.forms.select',
                    [
                        'name'      => 'webhook_method',
                        'label'     => 'Method' ,
                        'class'     => 'ajax-form-field' ,
                        'required'  => true,
                        'attr'      => '',
                        'data'      => [
                            'post'  => 'POST',
                            'get'   => 'GET'
                        ],
                        'value'     => isset($integration->data['webhook_method']) ? $integration->data['webhook_method'] : ''
                    ])
                </div>
        </div>
        </div>
    </div>
</div>

</div>
