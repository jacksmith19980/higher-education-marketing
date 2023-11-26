@if (isset($properties['required']))
data-required-fiels="{{json_encode($properties['required'])}}"
data-rule-filelistuploaded=true
data-msg-filelistuploaded="{{__('Please upload all required files')}}"
@endif

@if(isset($properties['validation']['required']))
	required="required"
	data-msg="{{$properties['validation']['required']}}"
	data-rule-required=true
@endif

@if(isset($properties['validation']['email']))
	data-rule-email=true
	data-msg-email="{{$properties['validation']['email']}}"
@endif

@if(isset($properties['validation']['maxlength']['number']))
	data-rule-maxlength = "{{$properties['validation']['maxlength']['number']}}"
	data-msg-maxlength = "{{isset($properties['validation']['maxlength']['message']) ? $properties['validation']['maxlength']['message'] : 'Invalid Length'}}"
@endif

@if(isset($properties['validation']['minlength']['number']))
	data-rule-minlength = "{{$properties['validation']['minlength']['number']}}"
	data-msg-minlength = "{{isset($properties['validation']['minlength']['message']) ? $properties['validation']['minlength']['message'] : 'Invalid Length'}}"
@endif
