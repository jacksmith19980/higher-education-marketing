@if ($params['repeater'] != null )
	@php
		$properties['class'] = $properties['class'] . ' repeated_field';
	@endphp
@endif

<div class="field_wrapper {{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" {{ (!$properties['show'])? 'data-hidden=true' : ' ' }}

	@smart($properties['smart'])

	@if (!isset($properties['logic']['type']))
		@php		
			$ref_field= $properties['logic']['field']
		@endphp 
		
	@else

		@php
		$ref_field = ( in_array($properties['logic']['type'] , ['checkbox']) ) ? $properties['logic']['field']."[]" : $properties['logic']['field'];
		@endphp 
		
	@endif

		data-field="{{$name}}"

		data-action="{{$properties['logic']['action']}}"

		data-reference="{{$ref_field}}"

		data-operator="{{$properties['logic']['operator']}}"

		@if (!is_array($properties['logic']['value']))
		
			data-value="{{$properties['logic']['value']}}"
			
		@else
		
			data-value="{{implode(",",$properties['logic']['value'])}}"

		@endif

	@endsmart
>

		<div class="form-group">

		 

		@if($properties['label']['show'])
		 		<label for="{{$name}}">{{ __($properties['label']['text']) }}
	
					@if(isset($properties['validation']['required'])) 
						<span class="text-danger">*</span>
					@endif

		 		</label>
		@endif



		<input  id="{{$name}}" 

				type="text" 

			    class="form-control-lg datepicker-autoclose form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{ ($properties['class']) }}" 

			    name="{{$name}}" 

		 		value="@if($value){{ $value }}@else{{ old($name) }}@endif" 

				

				@if(!$properties['label']['show']) 

						placeholder ="{{__($properties['placeholder'])}}@if(isset($properties['validation']['required']))  *@endif" 

				@endif
				
				@if(isset($properties['format']))
					data-format = "{{$properties['format']}}"
				@endif
				
				@if(isset($properties['startDate']))
				 		data-start-date = "{{$properties['startDate']}}"
				@endif
				
				@if(isset($properties['endDate']))
					data-end-date = "{{$properties['endDate']}}"
				@endif
				{{--  @dump($properties)  --}}
				@if(isset($properties['startView']))
				 		data-start-view = "{{$properties['startView']}}"
				@endif

				@include('front.applications.application-layouts.gbsg.partials._validation-messages');



		 		>



		 		@isset ($properties['helper'])

						<small id="{{$name}}" class="form-text text-info float-left helper-text">    	{{$properties['helper']}}

						</small>

				@endisset


		<div class="error_{{$name}} error_container"></div>


		 @if ($errors->has($name))

			        <span class="invalid-feedback" role="alert">

			            <strong>{{ $errors->first($name) }}</strong>

			        </span>

			    @endif

			</div>

</div>





