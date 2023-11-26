@php
if($educationable->type == 'Program'){
    $route = route('programs.show' , ['program' => $educationable]);
}elseif($educationable->type == 'Course'){
    $route = route('courses.show' , ['course' => $educationable]);
}
@endphp
<div id="education-{{$education->id}}" class="py-3 px-4 nav-application l-psuedu-border bg-grey-1" data-education-id="{{$education->id}}">
    <a class="btn btn-light bg-primary btn-toggler float-right-btn collapsed" data-toggle="collapse" href="" role="button" aria-expanded="false" aria-controls="appbodyCollapse">
    <i class="text-white mdi mdi-plus" data-toggle="tooltip" data-placement="top" title="" data-original-title="View More">
    </i>
    </a>
    <div class="py-2 pl-2 pr-0 row application-header justify-content-between pl-md-4" data-parent="#accordion_application_action" href="education-{{$education->id}}">
        <div class="col-11">
            <div class="d-flex">
                <div class="af-column-title">
                    <h5 class="mb-3">{{$educationable->type}}</h5>
                    <h4>
                        <span>
                            <a href="{{$route}}" target="_blank">
                                {{$educationable->title}}
                            </a>
                        </span>
                    </h4>
                    <div class="application-statuses d-flex flex-wrap mb-3 mb-md-0">
                        <p class="pr-3">
                            <strong>
                                {{__('Status')}}:
                            </strong>
                            @php

                            @endphp
                            <span
                            class="editable editable-click editable-open singleClick"
                                data-placement="top"
                                data-name="status"
                                data-type="select"
                                data-source="{{route('education.date.source' , [
                                        'education' => $education,
                                        'property'  => 'status',
                                ] )}}"
                                data-value="{{$education->status}}"
                                data-url="{{route('education.quick-edit.update' , [
                                    'education' => $education
                                ])}}"
                                >
                                {{ ucwords($education->status) }}
                            </span>
                        </p>
                        <p class="pr-3">
                            <strong>
                                {{__('Intake')}}:
                            </strong>
                            <span
                            class="editable editable-click editable-open singleClick"
                                data-placement="top"
                                data-name="date_id"
                                data-type="select"
                                data-source="{{route('education.date.source' , [
                                        'education' => $education,
                                        'property'  => 'intake',
                                    ] )}}"
                                data-value="{{($date = $education->date) ? $date->id : null}}"
                                data-url="{{route('education.quick-edit.update' , [
                                    'education' => $education
                                ])}}"
                                >
                                {{($date = $education->date) ? $date->title : null}}
                            </span>
                        </P>
                        <p class="pr-3">
                            <strong>
                                {{__('Campus')}}:
                            </strong>
                            @php
                            @endphp
                            <span
                            class="editable editable-click editable-open singleClick"
                                data-placement="top"
                                data-name="campus_id"
                                data-type="select"
                                data-source="{{route('education.date.source' , [
                                        'education' => $education,
                                        'property'  => 'campus',
                                    ] )}}"
                                data-value="{{ ($campus = $education->campus) ?  $campus->id  : null }}"
                                data-url="{{route('education.quick-edit.update' , [
                                    'education' => $education
                                ])}}"
                                >
                                {{ ($campus = $education->campus) ?  $campus->title  : null }}
                            </span>
                        </p>

                        <p class="pr-3">

                        @if($count = $education->allGroups->count())

                        <strong>
                            {{__( Str::plural('Cohort' , $count) )}}:
                        </strong>

                        @foreach ($education->allGroups as $group)
                            <span
                                class="editable editable-click editable-open singleClick mr-3"
                                    data-placement="top"
                                    data-name="group"
                                    data-type="select"
                                    data-source="{{route('education.date.source' , [
                                            'education' => $education,
                                            'property'  => 'group',
                                        ] )}}"
                                    data-value="{{ $group->id }}"
                                    data-url="{{route('education.quick-edit.update' , [
                                        'education' => $education,
                                        'current'   => $group->id
                                    ])}}"
                                    >
                                    {{ $group->title }}
                                </span>
                        @endforeach

                        @else
                            <strong>
                                {{__( Str::plural('Cohort' , $count) )}}:
                            </strong>

                                <span
                                class="editable editable-click editable-open singleClick mr-3"
                                    data-placement="top"
                                    data-name="group"
                                    data-type="select"
                                    data-source="{{route('education.date.source' , [
                                            'education' => $education,
                                            'property'  => 'group',
                                        ] )}}"
                                    data-value="{{ null }}"
                                    data-url="{{route('education.quick-edit.update' , [
                                        'education' => $education,
                                        'current'   => null
                                    ])}}"
                                    ></span>

                        @endif

                    </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-1 mt-2 px-0 d-flex justify-content-center align-items-center">
            <div class="btn-group more-optn-group float-right is-circle">
                            <button type="button"
                                class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt is-circle flat-btn"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu">
                                    <a class="dropdown-item" data-toggle="tooltip" href="javascript:void(0)" role="button" data-placement="top" data-original-title="{{__('Edit')}}"
                                    onclick="app.editEducation('{{route('edit.education' , [
                                        'student'   => $student,
                                        'education' => $education,
                                        'type'      => $educationable->type
                                    ])}}','','Edit Education' , this)">
                                        <i class="mr-1 text-white icon-note"></i><span class="pl-2 icon-text">{{__('Edit')}}</span>
                                    </a>

                                    <a class="dropdown-item" data-toggle="tooltip" href="javascript:void(0)" role="button" data-placement="top" data-original-title="{{__('Delete')}}"
                                    onclick="app.deleteElement('{{route('education.delete' , $education)}}','','data-education-id')">
                                        <i class="mr-1 text-white icon-trash"></i><span class="pl-2 icon-text">{{__('Delete')}}</span>
                                    </a>
                            </div>
                        </div>
        </div>
    </div>
    <div class="collapsed show" id="appbodyCollapse">
            <div class="pl-2 pl-md-3 accordion pl-md-4 tab-accordion">
                <div>
                    @include('back.students.education.sub-educationable' , [
                        'subEducations' => $education->subEducation
                    ])
                </div>
            </div>
        </div>
</div>
