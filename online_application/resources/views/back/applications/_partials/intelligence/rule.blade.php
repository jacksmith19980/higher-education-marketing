<div class="row">
<div class="col-md-5">
    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[logic_action]',
        'label'     	=> 'Action' ,
        'class'     	=> 'ajax-form-field' ,
        'required'  	=> true,
        'attr'      	=> '',
        'data'			=> [
        	'show'		=> 'Show Field',
        	'hide'		=> 'Hide Field',
        	'required'	=> 'Mak Field Required',

        ],
        'value'     	=> ''
    ])
</div>

<div class="col-md-1" style="padding-top: 36px;text-align: center;">
	<strong class="text-success"><em>if</em></strong>
	</div>

<div class="col-md-6">
	@php
		$applicationId = $application->id;
	@endphp

    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[logic_field]',
        'label'     	=> 'Field' ,
        'class'     	=> 'ajax-form-field select2 logic_field' ,
        'required'  	=> true,
        'attr'      	=> "onchange=app.smartFieldChanged(this) data-applicationid =$applicationId",
        'data'			=> $fields,
        'value'     	=> ''
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[logic_operator]',
        'label'     	=> 'Condition' ,
        'class'     	=> 'ajax-form-field' ,
        'required'  	=> true,
        'attr'      	=> 'onchange=app.smartFieldConditionChanged(this)',
        'data'			=> [
        	'equal'		=> 'Equals',
        	'contain'	=> 'Contains',
        	'empty'		=> 'Empty',
        	'not_empty'	=> 'Not Empty',
        ],
        'value'     	=> 'equal'
    ])
</div>

<div class="col-md-6 fieldValue"></div>
</div> <!-- row -->
