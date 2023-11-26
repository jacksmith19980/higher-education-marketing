@include('back.layouts.core.forms.'.$file,
	[
	    'name'      	=> 'properties[logic_value]',
	    'label'     	=> 'Value' ,
	    'class'     	=> 'ajax-form-field logic_value select2' ,
	    'required'  	=> true,
	    'data'			=> $data,
	    'attr'      	=> '',
	    'value'     	=> ''
	]
)

<input type="hidden" class="ajax-form-field" name="properties[logic_type]" value="{{$type}}">
