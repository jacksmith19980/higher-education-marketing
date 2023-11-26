@if(isset($properties['validation']['required'])) 
	required="required"
	data-msg="{{__($properties['validation']['required'])}}"
	data-rule-required=true
@endif

@if(isset($properties['validation']['email'])) 
	data-rule-email=true
	data-msg-email="{{__($properties['validation']['email'])}}"
@endif
	
@if(isset($properties['validation']['maxlength'])) 
	data-rule-maxlength = "{{__($properties['validation']['maxlength|number'])}}"
	data-msg-maxlength = "{{__($properties['validation']['maxlength'])}}"
@endif

@if(isset($properties['validation']['minlength'])) 
	data-rule-minlength = "{{__($properties['validation']['minlength|number'])}}"
	data-msg-minlength = "{{__($properties['validation']['minlength'])}}"
@endif