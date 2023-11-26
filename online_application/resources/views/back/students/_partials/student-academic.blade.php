@if (count($applicant->submissions))

    @php
        $studentGroup = $student->groups->first();
        $program = $studentGroup ? $studentGroup->program : null;
    @endphp

    @if ($program)

        <div id="nav-academic" class="tab-pane fade p-4" role="tabpanel" aria-labelledby="pills-invoices-tab">
            @foreach ($applicant->submissions as $submission)
                <div class="py-3 px-4 nav-application l-psuedu-border bg-grey-1">
                    <div class="py-2 pl-2 pr-0 row application-header justify-content-between pl-md-4"
                        data-parent="#accordion_application_action">


                        <div class="col-12 col-md-12">
                            <div class="d-flex">
                                <div class="af-column-title" style="width: 70%;">
                                    <h5 class="mb-3">{{ __('Program') }}</h5>
                                    <h4><span>{{ $program->title }}</span></h4>
                                    <div
                                        class="application-statuses d-flex flex-wrap mb-3 mb-md-0 justify-content-between">
                                        <div>
                                            <span class="pr-3 nsb"><i class="icon-clock text-secondary"></i>
                                                {{ __('Cohort') }}:</span>
                                            <span class="nl">
                                                {{ $studentGroup ? $studentGroup->title : '' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="pr-3 nsb"><i class="icon-clock text-secondary"></i>
                                                {{ __('Start Date') }}:</span>
                                            <span class="nl">
                                                {{ $studentGroup ? $studentGroup->start_date : '' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="pr-3 nsb"><i class="icon-clock text-secondary"></i>
                                                {{ __('End Date') }}:</span>
                                            <span class="nl">
                                                {{ $studentGroup ? $studentGroup->end_date : '' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="nsb"><i class="fas fa-building mr-1 text-muted"></i>
                                                {{ __('Campus') }}:</span>
                                            <span class="nl">
                                                {{ $studentGroup ? $studentGroup->campus->first()->title : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="student_academics_table" class="table tb-white">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ __('Course') }}</th>
                                    <th>{{ __('Cohort') }}</th>
                                    <th>{{ __('Semester') }}</th>
                                    <th></th>
                                </tr>
                            </thead>

                            @if ($submission and $program->courses)
                                @foreach ($program->courses as $course)
                                @php

                                    $group = App\Tenant\Models\Group::where('course_id', $course->id)->first();
                                    $semester = $group ? $group->semesters->first() : null;
                                    $instructor = ($group and $group->instructors) ? $group->instructors->first() : null;
                                    $dates = App\Tenant\Models\Date::where('object_id', $course->id)->first();
                                    $scheduel = ($dates and $dates->properties and isset($dates->properties['date_schudel'])) ? App\Tenant\Models\Schedule::find($dates->properties['date_schudel']) : null;
                                    $classroom = $scheduel ? $scheduel->classroomSlots->first()->classroom : null;
                                @endphp
                                    <tr>
                                        <td>
                                            @if( $dates and (new DateTime($dates->properties['end_date'])) < Carbon\Carbon::now() )
                                            <img src="/assets/images/icons/plus.png" onclick="app.toggleDetails(event)"
                                                alt="">
                                            @endif
                                        </td>
                                        <td>
                                            <div class="title @if($dates and (new DateTime($dates->properties['end_date'])) > Carbon\Carbon::now()) greyed @endif">
                                                {{ $course ? $course->title : '' }}
                                            </div>
                                            <div class="details">
                                                <div>
                                                    <span>{{ __('Teacher') }}:</span><span>{{ $instructor ? $instructor->first_name . ' ' . $instructor->last_name : '' }}</span>
                                                </div>
                                                <div><span>{{ __('Timeslot') }}:</span><span>{{ $scheduel ? (new DateTime($scheduel->start_time))->format('h:m a') : '' }}
                                                        {{ $scheduel ? (new DateTime($scheduel->end_time))->format('h:m a') : '' }}</span>
                                                </div>
                                                <div>
                                                    <span>{{ __('Start Date') }}:</span><span>{{ $dates ? $dates->properties['start_date'] : '' }}</span>
                                                </div>
                                                <div>
                                                    <span>{{ __('End Date') }}:</span><span>{{ $dates ? $dates->properties['end_date'] : '' }}</span>
                                                </div>
                                                <div>
                                                    <span>{{ __('Classroom') }}:</span><span>{{ $classroom ? $classroom->title : '' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title @if($dates and (new DateTime($dates->properties['end_date'])) > Carbon\Carbon::now()) greyed @endif">{{ $group ? $group->title : '' }}</div>
                                        </td>
                                        <td>
                                            <div class="title @if($dates and (new DateTime($dates->properties['end_date'])) > Carbon\Carbon::now()) greyed @endif">{{ $semester ? $semester->title : '' }}</div>
                                        </td>
                                        <td>
                                            @include('back.layouts.core.helpers.table-actions', [
                                                'buttons' => [
                                                    'edit' => [
                                                        'text' => 'View Course Attendence',
                                                        'icon' => 'icon-user-following',
                                                        'attr' => '',
                                                        'class' => '',
                                                    ],
                                                    'calendar' => [
                                                        'text' => 'View Course Grades',
                                                        'icon' => 'icon-calender',
                                                        'attr' => '',
                                                        'class' => '',
                                                    ],
                                                    'delete' => [
                                                        'text' => 'View Course Lessons',
                                                        'icon' => 'icon-notebook',
                                                        'attr' =>
                                                            'onclick=app.redirect("' .
                                                            route('lessons.index') .
                                                            '")',
                                                        'class' => '',
                                                    ],
                                                ],
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div id="nav-academic" class="tab-pane fade p-4" role="tabpanel" aria-labelledby="pills-invoices-tab">
            <div class="alert alert-warning">
                <strong>{{ __('No Results Found') }}</strong>
                <span class="d-block">{{ __('there are no data to show!') }}</span>
            </div>
        </div>
    @endif
@else
    <div id="nav-academic" class="tab-pane fade p-4" role="tabpanel" aria-labelledby="pills-invoices-tab">
        <div class="alert alert-warning">
            <strong>{{ __('No Results Found') }}</strong>
            <span class="d-block">{{ __('there are no data to show!') }}</span>
        </div>
    </div>
@endif
