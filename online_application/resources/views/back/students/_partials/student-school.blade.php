<div class="p-4 tab-pane fade l-psuedu-border bg-grey-1" id="nav-school" role="tabpanel" aria-labelledby="nav-school">
        @if ( $count = $applicant->groups->count() )
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th style="width:30%;"><strong>{{__('Number')}}</strong></th>
                        <td>
                            {{$applicant->uuid}}
                        </td>
                        <td>
                            <a href="javascript:void(0)"
                               onclick="app.updateStudentUuid(`{{route('students.uuid.update', $applicant)}}`, ``, `profile`)"
                               class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"
                               data-original-title="Edit Stage">
                                <i class="icon-pencil"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <th style="width:30%;"><strong>Cohort</strong></th>
                        <td>
                            @foreach ($applicant->groups as $group)
                                <span class="badge badge-secondary">{{$group->title}}</span>
                            @endforeach
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <th style="width:30%;"><strong>Program</strong></th>
                        <td>
                            @foreach ($applicant->groups as $group)
                                @if ($group && isset($group->program))
                                    <span class="badge badge-secondary">{{$group->program->title}}</span>
                                @endif
                            @endforeach
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <th style="width:30%;"><strong>Course</strong></th>
                        <td>
                            @foreach ($applicant->groups as $group)
                                @if ($group && isset($group->course))
                                    <span class="badge badge-secondary">{{$group->course->title}}</span>
                                @endif
                            @endforeach
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <th style="width:30%;"><strong>Campus</strong></th>
                        <td>
                            @php
                                $campus_title = []
                            @endphp
                            @foreach ($applicant->groups as $group)
                                @if ($group && isset($group->campus) && !in_array($group->campus->title, $campus_title))
                                    @php $campus_title[] = $group->campus->title;@endphp
                                    <span class="badge badge-secondary">{{$group->campus->title}}</span>
                                @endif
                            @endforeach
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
        @else
            @include('back.students._partials.student-no-results')
        @endif

</div>
