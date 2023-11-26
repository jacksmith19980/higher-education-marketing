<div class="{{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 {{$properties['wrapper']['class']}}" {{ (!$properties['show'])? 'data-hidden=true' : ' ' }}


	@smart($properties['smart'])

		@if (!isset($properties['logic']['type']))

			@php
				$ref_field= $properties['logic']['field']
			@endphp

		@else

			@php
			$ref_field = ( in_array($properties['logic']['type'] , ['checkbox'])) ? $properties['logic']['field']."[]" : $properties['logic']['field'];
			@endphp

		@endif


		data-field="{{$name}}"

		data-action="{{$properties['logic']['action']}}"

		data-reference="{{$ref_field}}"

		data-operator="{{$properties['logic']['operator']}}"

		<!-- THE DATA ATTR WERE HERE -->

		@if (!is_array($properties['logic']['value']))

			data-value="{{$properties['logic']['value']}}"

		@else

			data-value="{{implode(",",$properties['logic']['value'])}}"

		@endif

	@endsmart

>
	@php
			$file = FilesHelper::getFile($value);
	@endphp

	<div class="form-group">

			@if($properties['label']['show'])
			 	<label for="{{$name}}">{{ __($properties['label']['text']) }}
					@if(isset($properties['validation']['required']))
						<span class="text-danger" style="color:red">*</span>
					@endif
			 	</label>
			@endif

	    {{--  if there is file already uploaded  --}}
		@if ($value != null && $file )
					@include('front.applications.application-layouts.oiart.file.uploaded-file' , ['file' => $file ])
		@endif


		@php
			$allowed = (isset($properties['allowed']) ) ?  implode( "," ,$properties['allowed'] ):
			'jpg,gif,png,pdf,doc,docx,zip';
		@endphp

		<div class="fileuploader" data-allowed = "{{ $allowed  }}" data-uploadstr="{!! __('Drag & Drop Files') !!}"

			@isset ($properties['multiple'])
				data-multiple = {{ $properties['multiple'] }}
			@endisset

			data-upload = "{{route('school.file.upload' , $school)}}"
			data-destroy = "{{route('school.file.destroy', $school)}}"
			data-name = "{{$name}}"

			data-delete-btn-txt="{{__('Delete')}}"

			data-delete-message="{{__('Are you sure?')}}"

			data-delete-warning="{{__('You are going to delete this file permanently')}}"

			{{--  if there is file already uploaded  --}}
			@if ( $value != null && $file )
				style="display:none !important"
			@endif
		></div>

		@isset ($properties['helper'])
			<small id="{{$name}}" class="form-text text-info float-left helper-text">
				{{$properties['helper']}}
			</small>
		@endisset

		<div class="error_{{$name}} fileError"></div>

	<input type="text" style="opacity: 0;height: 0;margin-top: -10px;" class="{{$name}} form-control {{ $errors->has($name) ? ' is-invalid' : '' }} fileHolder" name="{{$name}}" style="width:100%" value="{{optional($file)->name}}"
		@include('front.applications.application-layouts.gbsg.partials._validation-messages');
	/>



</div>

</div>