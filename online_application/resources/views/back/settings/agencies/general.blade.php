<div class="col-md-10">
        <div class="card no-padding card-border">
            <div class="card-header">
                <h4 class="card-title">{{__('Main')}}</h4>
            </div>
            <div class="card-body" style="border:1px solid #f7f7f7;">
                <div class="row">
                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select',
                        [
                        'name' => 'enable_agencies_login',
                        'label' => 'Enable agencies login' ,
                        'class' => '',
                        'required' => false,
                        'attr' => $disabled,
                        'value' => isset($settings['agencies']['enable_agencies_login'])? $settings['agencies']['enable_agencies_login'] : "No",
                        'data' => [
                            "Yes" => 'Yes',
                            "No" => 'No',
                            ]
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('back.layouts.core.forms.select',
                        [
                        'name' => 'automatically_approve_agencies',
                        'label' => 'Automatically Approve Agencies' ,
                        'class' => '',
                        'required' => false,
                        'attr' => $disabled,
                        'value' => isset($settings['agencies']['automatically_approve_agencies'])? $settings['agencies']['automatically_approve_agencies'] : "Yes",

                        'data' => [
                            "Yes" => 'Yes',
                            "No" => 'No',
                            ]
                        ])
                    </div>
                </div>

            </div>
        </div>
    </div>
