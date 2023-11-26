<div class="card" style="width: 100%">
    <div class="card-header bg-info text-white" role="tab" id="headingOne">
        
        <h5 data-toggle="collapse" data-parent="#accordion_application_action" href="#collapse2" aria-expanded="true" aria-controls="collapseOne" class="mb-0">Send Notification Email</h5>

    </div>
    
    <div id="collapse2" class="collapse {{(!isset($action->properties))? " show" : ' ' }}" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-body">
          	
          	 @include('back.layouts.core.forms.hidden-input',
			    [
			        'name'      	=> 'actions[]',
			        'label'     	=> '' ,
			        'class'     	=>'' ,
			        'value'     	=> 'send-notification-email',
			        'placeholder'	=> '',
			        'helper'		=> '',
			        'required'  	=> true,
			        'attr'      	=> '' 
			    ])

			<div class="row">
				<div class="col-md-12">
					    @include('back.layouts.core.forms.text-input',
					    [
					        'name'      	=> 'action_properties[send-notification-email][send_to]',
					        'label'     	=> 'Recipients' ,
					        'class'     	=>'' ,
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
			            'name'          => 'action_properties[send-notification-email][attach_pdf]',
			            'label'         => '' ,
			            'class'         => '' ,
			            'required'      => false,
			            'attr'          => '',
			            'helper_text'   => 'Attach PDF Copy of the application data',
			            'value'         =>  isset($action->properties['attach_pdf']) ? $action->properties['attach_pdf'] : '',
			            'default'       => true,
			        ])

				</div>
				<div class="col-md-12">
					 @include('back.layouts.core.forms.html',
				    [
				        'name'      => 'action_properties[send-notification-email][email]',
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