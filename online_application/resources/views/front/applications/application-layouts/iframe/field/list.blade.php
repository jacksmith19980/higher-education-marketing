@php
$select2 = true;
@endphp

@if ($params['repeater'] != null )
	@php
		$original_name = $name;
		$select2 = false;
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

		<select class="form-control-lg {{($select2) ? 'select2' : ''}} form-control custom-select {{ (isset($properties['class']))? $properties['class'] : ' '}}" name="{{$name}}"  {{ (isset($properties['attr']))? $properties['attr'] : ' '}}
			@include('front.applications.application-layouts.iframe.partials._validation-messages')

			@include('front.applications.application-layouts.iframe.partials._sync' , $params)
		>

		@if(!$properties['label']['show'])    

			<option value="" selected="selected">{{__($label)}} @if(isset($properties['validation']['required']))* @endif</option>

		@else
			<option value="" selected="selected">{{__('--Select--')}}</option>

		@endif

		@php
			if($properties['listName'] == 'mautic_custom_field') {
				$data = $integrable->getFieldList($properties['mautic']['custom']);
			}
		@endphp

		@if ($data)

			@foreach ($data as $k=>$v)
						
					@if ( is_array($v) )
							
					<optgroup label="{{$k}}">

						@foreach ($v as $key => $item)

							<option value="{{$key}}" @selected($value,$key) {{ ' selected ="selected"' }} @endselected >{{__($item)}}</option>

						@endforeach
					
						</optgroup>
				
					@else 
						
						<option value="{{$k}}" @selected($value,$k) {{ ' selected ="selected"' }} @endselected >{{__($v)}}</option>
						
				@endif
					
					@endforeach
			
					@endif

	</select>

	

	@isset ($properties['helper'])

		<small id="{{$name}}" class="form-text text-info float-left helper-text">{{$properties['helper']}}
		</small>

	@endisset
		<div class="error_{{isset($original_name) ? $original_name : $name}} error_container"></div>
			@if ($errors->has($name))
				<span class="invalid-feedback" role="alert">
					<strong>{{ $errors->first($name) }}</strong>
				</span>
			@endif
		</div>
</div>





