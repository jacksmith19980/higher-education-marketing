
@php
    $excludes = ['program_name'];
    $mods = ['programs','campus','courses'];
    $fields = [
        //'program_name'  => __('Program Name'),
        'programs'      => __('Program'),
        'campus'        => __('Campus'),
        'date'          => __('Start Date'),
        'end_date'      => __('End Date'),
        'schedule'      => __('Schedule'),
    ];
    if(isset($value['programs'])){
        $value['programs_value'] = $value['programs'];
        $value['programs'] = SubmissionHelpers::getProgramName($value['programs']);
    }
    if(isset($value['campus'])){
        $value['campus_value'] = $value['campus'];
        $value['campus'] = SubmissionHelpers::getCampusName($value['campus']);
    }
    if(isset($value['courses'])){
        $value['courses_value'] = $value['courses'];
        $value['courses'] = SubmissionHelpers::getCourseName($value['courses']);
    }
@endphp



@foreach($fields as $slug => $name)
    @if( (!$studentView) || ($studentView && !empty($value[$slug])) )

        @php
            if(in_array($slug , $mods)){
                $filedValue = isset($value[$slug.'_value']) ? $value[$slug.'_value'] : '';
            }else{
                $filedValue = isset($value[$slug]) ? $value[$slug] : '';
            }


        @endphp

    <tr>
        <td class="title">{{$name}}</td>

        <td>
                <span class="{{!in_array($slug , $excludes) ? 'editable editable-click' : ''}}"
                data-placement="top"
                data-name="{{$field->name}}"

                data-value="{{$filedValue}}"

                @if(in_array( $slug , ['programs' , 'campus']))
                    data-type="select"
                    data-source="{{route('submission.field.data' , [
                        'object'        => 'program|' . $slug ,
                        'field'         => $field,
                        'value'         => isset($value['programs']) ? $value['programs'] : null,
                        'submission'    => $submission,
                        'application'   => $submission->application ,
                        'school'        => $school
                    ])}}"
                @elseif(in_array( $slug , ['schedule']))
                    data-type="text"

                @else
                    data-type="date"
                @endif

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
                {{ isset($value[$slug]) ? $value[$slug] : ''}}


            </span>
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
