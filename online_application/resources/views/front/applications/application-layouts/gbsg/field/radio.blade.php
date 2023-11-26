<div class="field_wrapper {{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" {{ (!$properties['show'])? 'data-hidden=true' : ' ' }}
	
	
	@smart($properties['smart'])
		data-field="{{$name}}"
		data-action="{{$properties['logic']['action']}}"
		data-reference="{{$properties['logic']['field']}}"
		data-operator="{{$properties['logic']['operator']}}"
		data-value="{{$properties['logic']['value']}}"
	@endsmart

>
		@foreach ($data as $val=>$lab)
			<div class="form-check form-check-inline {{$properties['class']}}">
				<div class="custom-control custom-radio">
					
					<input type="radio" class="custom-control-input" name="{{$name}}" id="{{$name}}_{{$val}}" value="{{$val}}">
				 	
				 	<label class="custom-control-label" for="{{$name}}_{{$val}}">{{ $lab }}</label>
				</div>
			</div>
		@endforeach

		@isset ($properties['helper'])
					<small id="{{$name}}" class="form-text text-info float-left helper-text">
						{{$properties['helper']}}
					</small>
				@endisset

				 	@if ($errors->has($name))
					        <span class="invalid-feedback" role="alert">
					            <strong>{{ $errors->first($name) }}</strong>
					        </span>
		@endif
</div>


