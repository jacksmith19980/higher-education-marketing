<div class="col-md-10">
<div class="card">
    <div class="text-white card-header bg-info" role="tab" id="headingOne">

        <h5 data-toggle="collapse" data-parent="#accordion_application_action" href="#integration_mautic" aria-expanded="true" aria-controls="collapseOne" class="float-left mb-0">Mautic</h5>

			<div class="float-right">
				@include('back.layouts.core.forms.switch',
					[
						'name'          => 'integration_mautic',
						'label'         => '',
						'class'         => 'switch' ,
						'required'      => false,
						'attr'          => 'data-size=mini data-on-text=Enabled data-off-text=Disabled onchange=app.toggleSchoolIntegration(this) data-integration=integration_mautic',
						'helper_text'   => '',
						'value'         => isset($settings['integrations']['integration_mautic'])? $settings['integrations']['integration_mautic'] : '',
						'default'       => 1
					])
			</div>
    </div>

    <div id="integration_mautic" class="collapse show" role="tabpanel" aria-labelledby="headingOne">

			<div class="card-body">

				@include('back.layouts.core.forms.hidden-input',
					[
						'name'      	=> 'integrations[]',
						'label'     	=> '' ,
						'class'     	=> '' ,
						'value'     	=> 'mautic',
						'placeholder'	=> '',
						'helper'		=> '',
						'required'  	=> true,
						'attr'      	=> 'disabled'
					])


				<div class="row">

					<div class="col-md-6">
						@include('back.layouts.core.forms.text-input',
						[
							'name'      => 'mautic_url',
							'label'     => 'Mautic URL' ,
							'class'     => '' ,
							'required'  => true,
							'attr'      => 'disabled',
							'value'     => isset($settings['integrations']['mautic_url'])? $settings['integrations']['mautic_url'] : ''
						])
					</div>

			<div class="col-md-6">
					@include('back.layouts.core.forms.text-input',
					[
						'name'      => 'mautic_username',
						'label'     => 'Mautic Username' ,
						'class'     =>'' ,
						'required'  => true,
						'attr'      => 'disabled',
						'value'     => isset($settings['integrations']['mautic_username'])? $settings['integrations']['mautic_username'] : ''
					])
				</div>

				<div class="col-md-6">
					@include('back.layouts.core.forms.text-input',
					[
						'name'      => 'mautic_password',
						'label'     => 'Mautic Password' ,
						'class'     =>'' ,
						'required'  => true,
						'attr'      => 'disabled',
						'value'     => isset($settings['integrations']['mautic_password'])? $settings['integrations']['mautic_password'] : ''
					])
				</div>
			</div>
        </div>
    </div>
</div>
</div>
