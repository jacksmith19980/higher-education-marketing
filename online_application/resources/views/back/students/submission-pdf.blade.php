@extends('back.layouts.pdf')

@section('page-title')
| {{$submission->student->name}} - {{ $application->title }}
@endsection
@section('content')
<div class="half left">
	<div class="logo" style="width: 30%;">
		{!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}
	</div>

</div>

<div class="half right">
	<p class="text-danger">{{ __('Student') }}: {{ $submission->student->name }}</p>
	<p class="text-danger">{{ __('Application Status') }}: {{ $submission->status }}</p>
	<p class="text-danger">{{ __('Submitted at') }}: {{ $submission->created_at }}</p>
	<p class="text-danger">{{ __('Updated at') }}: {{ $submission->updated_at }}</p>
</div>

<div class="clear"></div>

@php
	$uploadedImgs = [];
@endphp

@foreach ($application->sections_order as $sectionId)

@php
$section = $application->sections->filter(function($item) use ($sectionId) {
return $item->id == $sectionId;
})->first();
@endphp

<div class="card">
	<div class="card-header">
		<h4 class="text-white">{{ isset($section->properties['label']) ? $section->properties['label']  : $section->title }}</h4>
	</div>
	<div class="card-body">
		@if(is_array($section->fields_order))
		@foreach($section->fields_order as $fieldId)
		@php
			$field = $section->fields->filter(function($item) use ($fieldId) {
			return $item->id == $fieldId;
			})->first();

		@endphp

		@if ( isset($field) && isset( $submission->data[ $field->name ] ) && in_array($field->field_type, ['field' , 'file'] ) )
		<p>
			@if (is_array($submission->data[ $field->name]))
			@php
			//$data = json_encode($submission->data[ $field->name ]);
			//$data = implode(", " , $submission->data[ $field->name ]);
			$data = "
		<ul>";

			@endphp
			@if(count(array_filter($submission->data[ $field->name])))
			@foreach($submission->data[$field->name] as $item)
					@php
						$item = is_array($item) ? implode("|" , $item) : $item;
						$data .= "<li>" . $item . "</li>";
					@endphp
			@endforeach
			@php $data .= "</ul>";@endphp
		@else
		@php $data = ""; @endphp
		@endif

		@else

		@php
		$data = $submission->data[ $field->name ];
		@endphp

		@endif

		@if ($field->properties['type'] == 'signature')

			<strong>{{ SubmissionHelpers::extractFieldLabel($field)}}: </strong> <img src="{{$data}}" style="width: 100%" />

			@elseif ($field->field_type == 'file')

			<strong>{{ SubmissionHelpers::extractFieldLabel($field)}}: </strong>
			<a href="{{env('APP_URL')}}/submissions/applicants/files/view?fileName={{$data}}" target='_blank'>

				{{__('View File')}}
			</a>


		@else
			@if(($field->properties['type'] == 'course') || ($field->properties['type'] == 'program'))
			@if(array_key_exists('campus', $submission->data[ $field->name ]))
			@php $extractedDetails = SubmissionHelpers::extractApplicationCoursesDetailsWithCampus($submission->data[
			$field->name ]); @endphp
			<strong>{{ SubmissionHelpers::extractFieldLabel($field)}}: </strong>
			@foreach($extractedDetails as $ext)
			<p>{{ $ext }}</p>
			@endforeach
			@elseif(!array_key_exists('campus', $submission->data[ $field->name ]))
			@php $extractedDetails = SubmissionHelpers::extractApplicationCoursesDetailsWithoutCampus($submission->data[
			$field->name ]); @endphp
			<strong>{{ SubmissionHelpers::extractFieldLabel($field)}}: </strong>
			@foreach($extractedDetails as $ext)
			<p>{{ $ext }}</p>
			@endforeach
			@endif
		@else

		<strong>{{ SubmissionHelpers::extractFieldLabel($field)}}: </strong>{!! $data !!}
		@endif

		@endif
		</p>
		@endif

		@endforeach
		@endif
	</div>
</div>
@endforeach

@foreach($student_files as $file)
	@php
		$headers = get_headers(Storage::disk('s3')->temporaryUrl($file->name, \Carbon\Carbon::now()->addDays(6)), 1);
		if (strpos($headers['Content-Type'], 'image/') !== false) {
			array_push($uploadedImgs, Storage::disk('s3')->temporaryUrl($file->name, \Carbon\Carbon::now()->addDays(6)));
		}
	@endphp
@endforeach


@foreach ($uploadedImgs as $img)
	<img src="{{ $img }}" style="width: 100%;" />
@endforeach

@endsection
