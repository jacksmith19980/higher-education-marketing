<div class="card" style="width: 100% disabled" disabled>
    <div class="text-white card-header bg-info" role="tab" id="headingOne">

        <h5 data-toggle="collapse" data-parent="#accordion_application_action" href="#tracking_facebook"
            aria-expanded="true" aria-controls="collapseOne" class="float-left mb-0">{{__('Facebook Tracking')}}</h5>

        <div data-name="settings_tracking_facebook_switch" class="float-right">
            @include('back.layouts.core.forms.switch',
                [
                    'name'          => 'tracking_facebook',
                    'label'         => '',
                    'class'         => 'switch' ,
                    'required'      => false,
                    'attr'          => 'data-size=mini data-on-text=Enabled data-off-text=Disabled onchange=app.toggleTracking(this) data-tracking=tracking_facebook ' . $disabled,
                    'helper_text'   => '',
                    'value'         => isset($settings['tracking']['tracking_facebook'])? $settings['tracking']['tracking_facebook'] : '',
                    'default'       => 1
                ])
        </div>
    </div>

    <div id="tracking_facebook" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-body">
            @include('back.layouts.core.forms.hidden-input',
                [
                    'name'      	=> 'tracking[]',
                    'label'     	=> '' ,
                    'class'     	=> '' ,
                    'value'     	=> 'facebook',
                    'placeholder'	=> '',
                    'helper'		=> '',
                    'required'  	=> true,
                    'attr'      	=> 'disabled'
                ])

            <div class="row">
                <div data-name="settings_tracking_facebook_id" class="col-md-6">
                    @include('back.layouts.core.forms.text-input',
                    [
                        'name'      => 'facebook_id',
                        'label'     => 'Pixel Code ID' ,
                        'class'     => '' ,
                        'required'  => true,
                        'attr'      => 'disabled',
                        'value'     => isset($settings['tracking']['facebook_id'])? $settings['tracking']['facebook_id'] : ''
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
