<div class="field_wrapper {{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" {{ (!$properties['show'])? 'data-hidden=true' : ' ' }}

	@smart($properties['smart'])
		data-field="{{$name}}"
		data-action="{{$properties['logic']['action']}}"
		data-reference="{{$properties['logic']['field']}}"
		data-operator="{{$properties['logic']['operator']}}"
		data-value="{{$properties['logic']['value']}}"
	@endsmart
	
>
		<div class="form-group">
		 
		 @if($properties['label']['show'])<label for="{{$name}}">{{ __($properties['label']['text']) }}:</label>@endif

		<input  id="{{$name}}" 
				type="text" 
			    class="pickadate-select-year form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{ ($properties['class']) }}" 
			    name="{{$name}}" 
		 		value="@if($value){{ $value }}@else{{ old($name) }}@endif" 
				
				@if(!$properties['label']['show']) 
						placeholder ="{{$properties['placeholder']}}@if(isset($properties['validation']['required']))  *@endif" 
				@endif
				
				@include('front.applications.application-layouts.gbsg.partials._validation-messages');

		 		>

		 		@isset ($properties['helper'])
						<small id="{{$name}}" class="form-text text-info float-left helper-text">    	{{$properties['helper']}}
						</small>
				@endisset


		 @if ($errors->has($name))
			        <span class="invalid-feedback" role="alert">
			            <strong>{{ $errors->first($name) }}</strong>
			        </span>
			    @endif
			</div>
</div>


