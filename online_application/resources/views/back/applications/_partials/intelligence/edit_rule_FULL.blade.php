<div class="row">
<div class="col-md-3">
    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[logic_action]',
        'label'     	=> 'Action' ,
        'class'     	=> 'ajax-form-field' ,
        'required'  	=> true,
        'attr'      	=> 'disabled',
        'data'			=> [
        	'show'		=> 'Show Field',
        	'hide'		=> 'Hide Field',
        	'required'	=> 'Mak Field Required',

        ],
        'value'     	=> $logic['action']
    ])
</div>

<div class="col-md-1" style="padding-top: 36px;text-align: center;">
	<strong class="text-success"><em>if</em></strong>
	</div>

<div class="col-md-3">

    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[logic_field]',
        'label'     	=> 'Field' ,
        'class'     	=> 'ajax-form-field select2' ,
        'required'  	=> true,
        'attr'      	=> "disabled",
        'data'			=> [],
        'value'     	=> $logic['field']
    ])
</div>

<div class="col-md-2">
    @include('back.layouts.core.forms.select',
    [
        'name'      	=> 'properties[logic_operator]',
        'label'     	=> 'Condition' ,
        'class'     	=> 'ajax-form-field' ,
        'required'  	=> true,
        'attr'      	=> 'disabled',
        'data'			=> [
        	'equal'		=> 'Equals',
        	'contain'	=> 'Contains',
        	'empty'		=> 'Empty',
        	'not_empty'	=> 'Not Empty',
        ],
        'value'     	=> $logic['operator']
    ])
</div>

<div class="col-md-3">

    @include('back.layouts.core.forms.select',
        [
            'name'          => 'properties[logic_value]',
            'label'         => 'Value' ,
            'class'         => 'ajax-form-field logic_value' ,
            'required'      => true,
            'data'          => [],
            'attr'          => 'disabled',
            'value'         => $logic['value']
        ]
    )
</div>
</div> <!-- row -->
