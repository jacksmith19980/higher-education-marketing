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

			@if($properties['label']['show'])
			 	<label for="{{$name}}">{{ __($properties['label']['text']) }}:
					@if(isset($properties['validation']['required']))
						<span style="text-danger"> *</span>
					@endif
			 	</label>
			@endif


		@if (!isset($value) || !empty($value) )

			@include('front.applications.application-layouts.gbsg.file.uploaded-file' , ['fileName' => $value ])

		@else
					@php
						$allowed = (isset($properties['allowed']) ) ?  implode( "," ,$properties['allowed'] ):
						'jpg,gif,png,pdf,doc,docx';
					@endphp
					<div class="fileuploader abc" data-allowed = "{{ $allowed  }}"

						@isset ($properties['multiple'])
						    data-multiple = {{ $properties['multiple'] }}
						@endisset

						data-upload = "{{route('school.file.upload' , $school)}}"
						data-destroy = "{{route('school.file.destroy', $school)}}"
						data-name = "{{$name}}"

					></div>

					@isset ($properties['helper'])
						<small id="{{$name}}" class="badge badge-default badge-info form-text text-white float-left">    	{{$properties['helper']}}
						</small>
					@endisset

		@endif


		<input type="text" style="opacity: 0;height: 0;margin-top: -10px;" class="{{$name}}" name="{{$name}}" style="width:100%" value="{{$value}}"
			@include('front.applications.application-layouts.gbsg.partials._validation-messages');
		/>


	</div>

</div>