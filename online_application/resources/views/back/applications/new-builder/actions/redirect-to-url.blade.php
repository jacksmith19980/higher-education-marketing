<div class="card" style="width: 100%">
    <div class="card-header bg-info text-white" role="tab" id="headingOne">
        
        <h5 data-toggle="collapse" data-parent="#accordion_application_action" href="#collapse1" aria-expanded="true" aria-controls="collapseOne" class="mb-0">Redirect to URL</h5>

    </div>

    <div id="collapse1" class="collapse {{(!isset($action->properties))? " show" : ' ' }}" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-body">
          	
          	@include('back.layouts.core.forms.hidden-input',
			    [
			        'name'      	=> 'actions[]',
			        'label'     	=> '' ,
			        'class'     	=>'' ,
			        'value'     	=> 'redirect-to-url',
			        'placeholder'	=> '',
			        'helper'		=> '',
			        'required'  	=> true,
			        'attr'      	=> '' 
			    ])


			<div class="row">
				<div class="col-md-12">
					    @include('back.layouts.core.forms.text-input',
					    [
					        'name'      => 'action_properties[redirect-to-url][url]',
					        'label'     => 'Page URL' ,
					        'class'     =>'' ,
					        'value'     => isset($action->properties['url']) ? $action->properties['url'] : '',
					        'placeholder'	=> 'http://',
					        'required'  => true,
					        'attr'      => '' 
					    ])
				</div>
				<!-- <div class="col-md-6">
					 @include('back.layouts.core.forms.select',
				    [
				        'name'      => 'action_properties[redirect-to-url][target]',
				        'label'     => 'Open In',
				        'class'     => 'select2',
				        'value'     => '',
				        'required'  => true,
				        'attr'      => 'onchange=app.getApplicationActionDetails(this)',
				        'data'      => [
				        		'_blank' => 'New Window',
				        		'_slef'  => 'Same Window',
				        	]
				    ])
				</div> -->
			</div>

        </div>
    </div>
</div>