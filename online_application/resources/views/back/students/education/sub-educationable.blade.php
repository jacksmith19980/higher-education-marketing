
@foreach ($subEducations as $subEducation)
@php
if($subEducation->type == 'Program'){
    $route = route('programs.show' , ['program' => $educationable]);
}elseif($subEducation->type == 'Course'){
    $route = route('courses.show' , ['course' => $educationable]);
}
@endphp

<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 card-header" id="apph_pInfo">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center"
            data-toggle="collapse" data-target="#app_pInfo-{{$subEducation->id}}" aria-expanded="false"
            aria-controls="app_pInfo-{{$subEducation->id}}">
            <h4>

                {{$subEducation->educationable->title}}

            </h4>
            <i class="mdi mdi-plus text-primary"></i>
        </div>

    </div>

    <div id="app_pInfo-{{$subEducation->id}}" class="collapse" aria-labelledby="apph_pInfo"
        data-parent="#accordionExample">
        <div class="p-0 card-body">
            <table class="table mb-0 table-hover compressed-table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
                <tbody>
                    <tr>
                        <td class="title">
                            {{__('Status')}}
                        </td>
                        <td>

                            <span
                            class="editable editable-click editable-open singleClick"
                                data-placement="top"
                                data-name="status"
                                data-type="select"
                                data-source="{{route('education.date.source' , [
                                        'education' => $subEducation,
                                        'property'  => 'status',
                                    ] )}}"
                                data-value="{{$subEducation->status}}"
                                data-url="{{route('education.quick-edit.update' , [
                                    'education' => $subEducation
                                ])}}"
                                >
                                {{ ucwords($subEducation->status) }}
                            </span>

                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            {{__('Intake')}}
                        </td>
                        <td>
                            <span
                            class="editable editable-click editable-open singleClick"
                                data-placement="top"
                                data-name="date_id"
                                data-type="select"
                                data-source="{{route('education.date.source' , [
                                        'education' => $subEducation,
                                        'property'  => 'intake',
                                    ] )}}"
                                data-value="{{ ($date = $subEducation->date) ? $date->id : null}}"
                                data-url="{{route('education.quick-edit.update' , [
                                    'education' => $subEducation
                                ])}}"
                                >
                            {{ ($date = $subEducation->date) ? $date->title :  null}}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            {{__('Campus')}}
                        </td>
                        <td>
                            <span
                            class="editable editable-click editable-open singleClick"
                                data-placement="top"
                                data-name="campus_id"
                                data-type="select"
                                data-source="{{route('education.date.source' , [
                                        'education' => $subEducation,
                                        'property'  => 'campus',
                                    ] )}}"
                                data-value="{{ ($campus = $subEducation->campus) ?  $campus->id  : null }}"
                                data-url="{{route('education.quick-edit.update' , [
                                    'education' => $subEducation
                                ])}}"
                                >
                                {{ ($campus = $subEducation->campus) ?  $campus->title  : null }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                    @if($count = $education->allGroups->count())
                        <td class="title">
                            {{__( Str::plural('Cohort' , $count) )}}:
                        </td>
                        <td>
                            @foreach ($subEducation->allGroups as $group)
                            <span
                                class="editable editable-click editable-open singleClick mr-3"
                                    data-placement="top"
                                    data-name="group"
                                    data-type="select"
                                    data-source="{{route('education.date.source' , [
                                            'education' => $subEducation,
                                            'property'  => 'group',
                                        ] )}}"
                                    data-value="{{ $group->id }}"
                                    data-url="{{route('education.quick-edit.update' , [
                                        'education' => $subEducation,
                                        'current'   => $group->id
                                    ])}}"
                                    >
                                    {{ $group->title }}
                                </span>
                            @endforeach
                        </td>

                        @else

                            <td class="title">
                                {{__( 'Cohort' )}}:
                            </td>
                            <td>
                                <span
                                    class="editable editable-click editable-open singleClick mr-3"
                                        data-placement="top"
                                        data-name="group"
                                        data-type="select"
                                        data-source="{{route('education.date.source' , [
                                                'education' => $subEducation,
                                                'property'  => 'group',
                                            ] )}}"
                                        data-value=""
                                        data-url="{{route('education.quick-edit.update' , [
                                            'education' => $subEducation,
                                            'current'   => 0
                                        ])}}"
                                ></span>
                            </td>


                        @endif
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach
