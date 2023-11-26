<div class="col-md-10">
        <div class="card no-padding card-border">
            <div class="card-header">
                <h4 class="card-title">{{__('Mautic')}}</h4>
            </div>
            <div class="card-body" style="border:1px solid #f7f7f7;">
                <div class="row">
                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select',
                        [
                        'name' => 'load_mautic_agencies',
                        'label' => 'Load agencies from Mautic' ,
                        'class' => '',
                        'required' => false,
                        'attr' => $disabled,
                        'value' => isset($settings['agencies']['load_mautic_agencies'])? $settings['agencies']['load_mautic_agencies'] :
                        "No",
                        'data' => [
                            "Yes" => 'Yes',
                            "No" => 'No',
                            ]
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select', [
                            'name' => 'mautic_agent_push',
                            'label' => __('Push new accounts to Mautic') ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'placeholder' => 'Select',
                            'data' => [
                                'Yes'   => 'Yes',
                                'No'    => 'No',
                            ],
                            'value' => isset($settings['agencies']['mautic_agent_push'])? $settings['agencies']['mautic_agent_push'] : 'Yes',
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select', [
                            'name' => 'mautic_agent_default_campus',
                            'label' => 'Campus' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'placeholder' => 'Select Campus',
                            'data' => MauticHelper::getMauticListField('campus'),
                            'value' => isset($settings['agencies']['mautic_agent_default_campus'])? $settings['agencies']['mautic_agent_default_campus'] : null,
                        ])
                    </div>

                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select', [
                            'name' => 'mautic_agent_contact_type',
                            'label' => 'Contact Type' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'placeholder' => 'Select Contact Type',
                            'data' => MauticHelper::getContactTypes(),
                            'value' => isset($settings['agencies']['mautic_agent_contact_type'])? $settings['agencies']['mautic_agent_contact_type'] : null,
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select', [
                            'name' => 'mautic_agent_request_type',
                            'label' => 'Request Type' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'placeholder' => 'Select Request Type',
                            'data' => MauticHelper::getMauticListField('request_type'),
                            'value' => isset($settings['agencies']['mautic_agent_request_type'])? $settings['agencies']['mautic_agent_request_type'] : null,
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select', [
                            'name' => 'mautic_agent_stage',
                            'label' => 'Default Stage' ,
                            'class' => '',
                            'required' => false,
                            'attr' => $disabled,
                            'placeholder' => 'Select Stage',
                            'data' => $stages,
                            'value' => isset($settings['agencies']['mautic_agent_stage'])? $settings['agencies']['mautic_agent_stage'] : null,
                        ])
                    </div>

                </div>

            </div>
        </div>
    </div>
