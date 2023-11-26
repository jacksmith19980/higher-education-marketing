@php
    $isAdmin = isset($isAdmin) ? $isAdmin : false;
    $can_edit = isset($can_edit) ? $can_edit : false;
@endphp
@foreach ($submission->application->sections as $section)
<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 card-header" id="apph_pInfo-{{$section->id}}-{{$application->id}}">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center"
            data-toggle="collapse" data-target="#app_pInfo-{{$section->id}}-{{$submission->id}}" aria-expanded="false"
            aria-controls="app_pInfo">
            <h4>{{ ($section->properties['label'])? $section->properties['label'] : $section->title }}</h4>
            <i class="mdi mdi-plus text-primary"></i>
        </div>

    </div>

    <div id="app_pInfo-{{$section->id}}-{{$submission->id}}" class="collapse" aria-labelledby="apph_pInfo"
        data-parent="#accordionExample">
        <div class="p-0 card-body">
            <table class="table mb-0 table-hover compressed-table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
                <tbody>
                    @foreach ($section->fields as $field)

                        @php
                            if(isset($submission->data[$field->name])){

                                $value = $submission->data[$field->name];

                                if(is_array($value) && (isset($field->properties['type']) && $field->properties['type'] != 'program'))
                                {
                                    $value = implode('|',$value);
                                }
                            }else{
                                $value = '';
                            }
                        @endphp

                        @if((isset($field->properties['type']) && $field->properties['type'] == 'program'))
                            {{-- Show Main Program Data--}}

                            @include('front.applications.application-layouts.rounded.review.program-data', [
                                    'field'          => $field,
                                    'value'          => $value,
                                    'submission'     => $submission,
                                    'isAdmin'        => $isAdmin,
                                    'can_edit'       => $can_edit,
                                    'studentView'    => $studentView
                                ])

                            {{--  Show Custom Fields  --}}
                            @if(isset($field->properties['customFields']))

                                @include('front.applications.application-layouts.rounded.review.program-custom-fields', [
                                    'field'         => $field,
                                    'submission'    => $submission,
                                    'isAdmin'       => $isAdmin,
                                    'studentView'   => $studentView
                                ])

                            @endif

                        @else

                        <tr>
                            <td class="title">{{$field->label}}</td>
                            <td>
                                @if (isset($can_edit))

                                    @if ($can_edit)
                                        @isset($value)
                                            @include('back.students._partials.student-application-field' , [
                                                'value' => $value,
                                                'type'  => $field->field_type,
                                                'isAdmin'   => (isset($isAdmin) && $isAdmin) ? $isAdmin : false
                                                ])
                                        @endisset
                                    @else
                                        @isset($value)
                                            {{$value}}
                                        @endisset
                                    @endif


                                @else
                                    @isset($value)
                                        @include('back.students._partials.student-application-field' , ['value' => $value, 'type' => $field->field_type])
                                    @endisset
                                @endif
                            </td>

                            @if (isset($isAdmin) && $isAdmin)
                                <td>
                                    @isset($value)
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
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach
