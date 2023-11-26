<div class="{{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" 

	@smart($properties['smart'])

		data-field="{{$name}}"

		data-action="{{$properties['logic']['action']}}"

		data-reference="{{$properties['logic']['field']}}"

		data-operator="{{$properties['logic']['operator']}}"

		data-value="{{$properties['logic']['value']}}"

	@endsmart

>

	{!! __($properties['content']) !!}

</div>