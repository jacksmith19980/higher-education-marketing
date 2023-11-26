@if ($params['repeater'] != null )
	@php
		$properties['class'] = $properties['class'] . ' repeated_field';
	@endphp
@endif

@php
        $defaultValue =  SubmissionHelpers::getDefaultValue( $field );
        $readOnly = ( isset($defaultValue) && (!isset($properties['editable']) || !$properties['editable'] )) ?  "readonly" : false;
    @endphp

    @if (!empty(trim($value)) )
        
    @php
        $defaultValue = $value;	
    @endphp

@endif
<input class="field_wrapper" id="{{$name}}" type="hidden" name="{{$name}}" value="@if($defaultValue){{ $defaultValue }}@else{{ $properties['default'] }}@endif" required="required">