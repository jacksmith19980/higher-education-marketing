@if (!is_array($value))

    @if ($type == 'file')
            @if($value)
                <a href="{{route('file.show' , ['fileName' => $value])}}" target="_blank" class="btn btn-light">{{__('View File')}}</a>
            @endif
    @elseif (strpos($value, 'data:image/') !== false)

            <img src="{{$value}}" class="img-responsive w-100">

    @elseif($type == 'field')
        <span class="editable editable-click"
            data-placement="top"
            data-name="{{$field->name}}"
            data-value="{{$value}}"

            @if(isset($field->properties['type']) && $field->properties['type'] == 'email')
                data-type="email"
            @endif


            @if(isset($field->properties['type']) && $field->properties['type'] == 'date')
                data-type="date"
                data-format="yyyy-mm-dd",
                data-viewformat="yyyy-mm-dd"
            @endif

            @if(isset($field->properties['type']) && $field->properties['type'] == 'list')
                data-type="select"
                data-source="{{route('submission.field.data' , ['field' => $field , 'application' => $submission->application , 'school' => $school])}}"
            @endif


            data-url="{{route('application.field.edit' , ['field' => $field , 'submission' => $submission , 'school' => $school])}}"


            @if(isset($field->properties['validation']) && $field->properties['validation'])
                data-validation='{{ json_encode($field->properties['validation']) }}'
            @endif

            >


            {{ $value }}
        </span>


    @endif

@else
    @if(
        (isset($field->properties['type']) && $field->properties['type'] == 'course') ||
        (isset($field->properties['type']) && $field->properties['type'] == 'program')
    )

        @if(array_key_exists('campus', $value))
           @php $extractedDetails = SubmissionHelpers::extractApplicationCoursesDetailsWithCampus($value); @endphp
           @foreach($extractedDetails as $ext)
               <p>{{ $ext }}</p>
           @endforeach

        @elseif(!array_key_exists('campus', $value))
            @php $extractedDetails = SubmissionHelpers::extractApplicationCoursesDetailsWithoutCampus($value); @endphp
            @foreach($extractedDetails as $ext)
               <p>{{ $ext }}</p>
           @endforeach
       @endif


    @else
        @foreach ($value as $key => $val)
            @if(is_array($val))
                @foreach ($val as $k => $v)
                    <p class="mb-0">{{ $v }}</p>
                @endforeach
            @else
                <p class="mb-0">{{ $val }}</p>
            @endif

        @endforeach
    @endif

@endif
