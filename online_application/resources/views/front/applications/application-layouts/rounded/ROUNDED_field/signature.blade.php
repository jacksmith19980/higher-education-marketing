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
		@if(isset($params['order']))
		@php
			$ref_field = $ref_field.'[' . $params['order'] . ']';
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

	<div class="form-group canvas-wrapper" style="background-color: #fff">

		@if($properties['label']['show'])
		 		<label for="{{$name}}">{{ __($properties['label']['text']) }}
	
					@if(isset($properties['validation']['required'])) 
						<span class="text-danger">*</span>
					@endif

		 		</label>
		@endif

		<input  id="{{$name}}" 
				type="text" 
			    class="{{$name}} form-control{{ $errors->has($name) ? ' is-invalid' : '' }} {{ ($properties['class']) }}" 
				name="{{$name}}" 
				style="width:1px;height:1px;padding:0;margin:0;opacity:0"
		 		value="@if($value){{ $value }}@else{{ old($name) }}@endif" 
				
				@if(!$properties['label']['show']) 
					
					placeholder ="{{$properties['placeholder']}}@if(isset($properties['validation']['required']))  *@endif" 

				@endif

				@include('front.applications.application-layouts.gbsg.partials._validation-messages')
		 		>


		<canvas id="{{$name}}" class="signature-canvas" data-image-holder="{{$name}}" style="touch-action: none;border: 2px solid #CCC;display: block;" width="664" height="300"></canvas>

		@isset ($properties['helper'])
			<small id="{{$name}}" class="float-left form-text text-info helper-text">
				{{__($properties['helper'])}}
			</small>
		@endisset

		{{-- <a href="javascript:void(0)" class="ajax-file-upload-red float-right clear_{{$name}}" style="margin-top: 15px;"  onclick="app.clearSignature('{{$name}}')" >Clear</a> --}}

		<div class="error_{{$name}} error_container"></div>
		
		<div class="clearfix" class="m-10"></div>
		


		 	@if ($errors->has($name))

			        <span class="invalid-feedback" role="alert">
			            <strong>{{ $errors->first($name) }}</strong>
			        </span>
			@endif

	</div>



</div>











