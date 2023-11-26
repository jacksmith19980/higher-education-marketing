@include('back.layouts.core.forms.select',
	[
		'name'      	=> $name,
		'label'     	=> $label ,
		'class'     	=> 'ajax-form-field logic_value select2' ,
		'required'  	=> isset($required) ? $required : false,
		'data'			=> $data,
		'attr'      	=> isset($attr) ? $attr : '',
		'placeholder'   => isset($placeholder) ? $placeholder : 'Select one',
		'value'     	=> isset($value) ? $value : '',
	]
)
