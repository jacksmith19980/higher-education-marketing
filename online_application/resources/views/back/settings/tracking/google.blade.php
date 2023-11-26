<div class="card" style="width: 100% disabled" disabled>
    <div class="text-white card-header bg-info" role="tab" id="headingOne">

        <h5 data-toggle="collapse" data-parent="#accordion_application_action" href="#tracking_google" aria-expanded="true" aria-controls="collapseOne" class="float-left mb-0">{{__('Google Tracking')}}</h5>

        <div data-name="settings_tracking_google_switch" class="float-right">
        	@include('back.layouts.core.forms.switch',
					[
						'name'          => 'tracking_google',
						'label'         => '',
						'class'         => 'switch' ,
						'required'      => false,
						'attr'          => 'data-size=mini data-on-text=Enabled data-off-text=Disabled onchange=app.toggleTracking(this) data-tracking=tracking_google ' . $disabled,
						'helper_text'   => '',
						'value'         => isset($settings['tracking']['tracking_google'])? $settings['tracking']['tracking_google'] : '',
						'default'       => 1
					])
        </div>
    </div>
    <div id="tracking_google" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-body">
				@include('back.layouts.core.forms.hidden-input',
					[
						'name'      	=> 'tracking[]',
						'label'     	=> '' ,
						'class'     	=> '' ,
						'value'     	=> 'gtm',
						'placeholder'	=> '',
						'helper'		=> '',
						'required'  	=> true,
						'attr'      	=> 'disabled'
					])


			<div class="row">
				<div data-name="settings_tracking_google_gtm_id" class="col-md-6">
						@include('back.layouts.core.forms.text-input',
						[
							'name'      => 'gtm_id',
							'label'     => 'Tag Manager ID' ,
							'class'     => '' ,
							'required'  => false,
							'attr'      => 'disabled',
							'value'     => isset($settings['tracking']['gtm_id'])? $settings['tracking']['gtm_id'] : ''
						])
			    </div>
				<div data-name="settings_tracking_google_analytics" class="col-md-6">
			        @include('back.layouts.core.forms.text-input',
			        [
							'name'      => 'analytics_id',
							'label'     => 'Google Analytics' ,
							'class'     => '' ,
							'required'  => false,
							'attr'      => 'disabled',
							'value'     => isset($settings['tracking']['analytics_id'])? $settings['tracking']['analytics_id'] : ''
			        ])
			    </div>
			</div>
		</div>
    </div>
</div>
