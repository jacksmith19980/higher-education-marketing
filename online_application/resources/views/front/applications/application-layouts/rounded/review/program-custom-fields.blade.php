@php

    $customFields = SubmissionHelpers::extractCustomFields($field->properties['customFields']);
  @endphp


@foreach($customFields as $slug => $name)

    @if( (!$studentView) && isset($value[$slug]))
    <tr>
        <td class="title">{{$name}}</td>
        <td>

            <span class="editable editable-click"
                data-placement="top"
                data-name="{{$field->name}}"
                data-value="{{$value[$slug]}}"

                data-url="{{route('application.field.edit' , [
                        'object'        => $slug,
                        'field'         => $field,
                        'submission'    => $submission,
                        'school'        => $school
                        ])}}"


                @if(isset($field->properties['validation']) && $field->properties['validation'])
                    data-validation='{{ json_encode($field->properties['validation']) }}'
                @endif
                >
                {{$value[$slug]}}
        </td>


        @if (isset($isAdmin) && $isAdmin)
            <td>
                @isset($submission->data[$field->name])
                <a href="javascript:void(0)" class="ml-2 mr-2 btn btn-circle small-btn btn-light text-muted" data-toggle="tooltip"
                    onclick="app.resyncField({{ $submission->id }},{{ $submission->student->id }} ,this)"
                    data-field-name="{{$field->name}}"
                    data-placement="top" title="{{__('Resync Field')}}">
                    <i class="ti-reload"></i>
                </a>
                @endif
            </td>
        @endif

    </tr>

    @endif

@endforeach
