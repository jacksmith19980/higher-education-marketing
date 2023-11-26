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

		@if($properties['label']['show'])
		 		<label for="{{$name}}">{{ __($properties['label']['text']) }}
	
					@if(isset($properties['validation']['required'])) 
						<span class="text-danger">*</span>
					@endif

		 		</label>
		@endif 
		<br />


		@foreach ($data as $val=>$lab)

			<div class="form-check form-check-inline {{$properties['class']}}">

				<div class="custom-control custom-radio">
					
					@php
						$is_checked = '';
					@endphp

					
					@if (isset($value))

						@if ($value == $val)
							
							@php
								$is_checked = 'checked';
							@endphp

						@endif
					
					@endif


					<input type="radio" class="custom-control-input" name="{{$name}}" id="{{$name}}_{{$val}}" 
						value="{{$val}}" {{$is_checked}} 
						
						@include('front.applications.application-layouts.gbsg.partials._validation-messages')
					>

				 	<label class="custom-control-label" for="{{$name}}_{{$val}}">{!! __($lab) !!}</label>

				</div>

			</div>

		@endforeach

		<div class="error_{{$name}} error_container"></div>

		@isset ($properties['helper'])

					<small id="{{$name}}" class="form-text text-info float-left helper-text">

						{{__($properties['helper'])}}

					</small>

				@endisset



				 	@if ($errors->has($name))

					        <span class="invalid-feedback" role="alert">

					            <strong>{{ __($errors->first($name)) }}</strong>

					        </span>

		@endif

</div>





