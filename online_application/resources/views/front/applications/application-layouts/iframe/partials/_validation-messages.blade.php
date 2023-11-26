@if(isset($properties['validation']['required'])) 
	required="required"
	data-msg="{{$properties['validation']['required']}}"
	data-rule-required=true
@endif

@if(isset($properties['validation']['email'])) 
	data-rule-email=true
	data-msg-email="{{$properties['validation']['email']}}"
@endif
	
@if(isset($properties['validation']['maxlength'])) 
	data-rule-maxlength = "{{$properties['validation']['maxlength|number']}}"
	data-msg-maxlength = "{{$properties['validation']['maxlength']}}"
@endif

@if(isset($properties['validation']['minlength'])) 
	data-rule-minlength = "{{$properties['validation']['minlength|number']}}"
	data-msg-minlength = "{{$properties['validation']['minlength']}}"
@endif