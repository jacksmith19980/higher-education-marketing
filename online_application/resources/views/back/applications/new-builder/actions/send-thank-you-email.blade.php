<div class="card" style="width: 100%">
<div class="card-header bg-info text-white" role="tab" id="headingOne">
	<h5 data-toggle="collapse" data-parent="#accordion_application_action" href="#collapse3" aria-expanded="true" aria-controls="collapseOne" class="mb-0">{{__('Send Thank You Email')}}</h5>
</div>
<div id="collapse3" class="collapse {{(!isset($action->properties))? " show" : ' ' }}" role="tabpanel" aria-labelledby="headingOne">
	<div class="card-body">
			@include('back.layouts.core.forms.hidden-input',
			[
				'name'      	=> 'actions[]',
				'label'     	=> '' ,
				'class'     	=>'' ,
				'value'     	=> 'send-thank-you-email',
				'placeholder'	=> '',
				'helper'		=> '',
				'required'  	=> true,
				'attr'      	=> ''
			])
		<div class="row">
			<div class="col-md-12">
					@include('back.layouts.core.forms.html',
				[
					'name'      => 'action_properties[send-thank-you-email][email]',
					'label'     => 'Email',
					'class'     => '',
					'value'     => isset($action->properties['email']) ? $action->properties['email'] : '',
					'required'  => true,
					'attr'      => '',
				])
			</div>
		</div>
	</div>
</div>
</div>
