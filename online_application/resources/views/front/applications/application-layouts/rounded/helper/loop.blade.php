<div class="{{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" 
	@smart($properties['smart'])
		data-field="{{$name}}"
		data-action="{{$properties['logic']['action']}}"
		data-reference="{{$properties['logic']['field']}}"
		data-operator="{{$properties['logic']['operator']}}"
		data-value="{{$properties['logic']['value']}}"
	@endsmart
>
	<div class="repeat_fields_wrapper_{{$name}} row"></div>
		
		<a href="javascript:void(0)" class="{{$properties['class']}} field_repeater btn btn-success" data-fields = "{{implode("," , $properties['fields'])}}" onclick="app.repeatFields(this , 'repeat_fields_wrapper_{{$name}}')">{{__($properties['button']['text'])}}
		</a>
		
	<div class="clearfix clear"></div>
</div>