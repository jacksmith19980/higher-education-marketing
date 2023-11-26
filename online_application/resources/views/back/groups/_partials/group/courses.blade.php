<div class="col-12 col-md-12">
    <div class="d-flex">
        <div class="af-column-title" style="width: 70%;">
            <div
                class="application-statuses d-flex flex-wrap mb-3 mb-md-0 justify-content-between">
                <div>
                    <span class="nsb"><i class="fas fa-users mr-1 text-muted"></i>
                        {{ __('Cohort') }}:</span>
                    <span class="nl">
                        {{ $group ? $group->title : '' }}
                    </span>
                </div>
                <div>
                    <span class="nsb"><i class="fas fa-clock text-secondary"></i>
                        {{ __('Start Date') }}:</span>
                    <span class="nl">
                        {{ $group ? $group->start_date : '' }}
                    </span>
                </div>
                <div>
                    <span class="nsb"><i class="fas fa-clock text-secondary"></i>
                        {{ __('End Date') }}:</span>
                    <span class="nl">
                        {{ $group ? $group->end_date : '' }}
                    </span>
                </div>
                <div>
                    <span class="nsb"><i class="fas fa-building mr-1 text-muted"></i>
                        {{ __('Campus') }}:</span>
                    <span class="nl">
                        {{ $group ? $group->campus->first()->title : '' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@if (count($group->program->courses))
    <div style="margin-top: 24px;">
        <div class="table-responsive" style="overflow-x: visible!important;">

            <table id="group_courses_list" class="table tb-white" style="border-top: 1.5px solid black!important;">

                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th style="width: 220px!important;">{{ __('Course') }}</th>
                        <th>{{ __('Cohort') }}</th>
                        <th>{{ __('Semester') }}</th>
                        <th>{{ __('Grade') }}</th>
                        <th></th>
                    </tr>
                </thead>

                @foreach ($group->program->courses as $course)

                <tbody>
                    <tr>
                        <td style="display:none;"></td>
                        <td>
                            <img src="/assets/images/icons/plus.png" onclick="app.toggleDetails(event)" alt="+">
                        </td>
                        <td style="width: 220px!important;">
                            <div class="title">
                                @php
                                    $semester = $group->semesters->first();
                                    $instructors = array();
                                    foreach ($course->lessons as $lesson) {
                                        if(isset($lesson->instructor) && isset($lesson->instructor->id)) {
                                            $instructors[$lesson->instructor->id] = $lesson->instructor->first_name . ' ' . $lesson->instructor->last_name;
                                        }
                                    }
                                    $dates = App\Tenant\Models\Date::where('object_id', $course->id)->first();
                                    $scheduel = ($dates and $dates->properties and isset($dates->properties['date_schudel'])) ? App\Tenant\Models\Schedule::find($dates->properties['date_schudel']) : null;
                                    $classroom = $scheduel ? $scheduel->classroomSlots->first()->classroom : null;
                                @endphp
                                {{ $course ? $course->title : '' }}
                            </div>
                            <div class="details">
                                <div>
                                    <span>{{ __('Teachers') }}:</span>
                                    <span>
                                        @foreach ($instructors as $i)
                                            <br>{{$i}}
                                        @endforeach
                                    </span>
                                </div>
                                <div><span>{{ __('Timeslot') }}:</span>
                                    <span>
                                        {{ $scheduel ? (new DateTime($scheduel->start_time))->format('h:m a') : '' }}
                                        {{ $scheduel ? (new DateTime($scheduel->end_time))->format('h:m a') : '' }}
                                    </span>
                                </div>
                                <div>
                                    <span>{{ __('Start Date') }}:</span>
                                    <span>
                                        {{ $dates ? $dates->properties['start_date'] : '' }}
                                    </span>
                                </div>
                                <div>
                                    <span>{{ __('End Date') }}:</span>
                                    <span>
                                        {{ $dates ? $dates->properties['end_date'] : '' }}
                                    </span>
                                </div>
                                <div>
                                    <span>{{ __('Classroom') }}:</span>
                                    <span>
                                        {{ $classroom ? $classroom->title : '' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="title">{{ $group ? $group->title : '' }}</div>
                        </td>
                        <td>
                            <div class="title">{{ $semester ? $semester->title : '' }}</div>
                        </td>
                        <td>
                            G+
                        </td>
                        <td>
                            @php
                            $buttons = [
                                ["text"=>"View Course Attendance", "icon"=>"fas fa-users", "class"=>"", "url"=>"javascript:get_group_course($course->id, $group->id, 'group_course_attendances')"],
                                ["text"=>"View Course Grades", "icon"=>"far fa-newspaper", "class"=>"", "url"=>"javascript:get_group_course($course->id, $group->id, 'group_course_grades')"],
                                ["text"=>"View Course Lessons", "icon"=>"fas fa-graduation-cap", "class"=>"", "url"=>"javascript:get_group_course($course->id, $group->id, 'group_course_lessons')"],
                                ["text"=>"Manage Course", "icon"=>"fas fa-cubes", "class"=>"", "url"=>"javascript:open_course_quote_builder($course->id)"]
                            ]
                            @endphp
                            <div class="btn-group more-optn-group float-right">
                                <button type="button"
                                class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                                <div class="dropdown-menu">
                                    @foreach ($buttons as $button)
                                            <a class="dropdown-item {{$button['class']}}" href="{{$button['url']}}">
                                                <i class="{{$button['icon']}}"></i> <span  class="icon-text">{!!__($button['text'])!!}</span>
                                            </a>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>

                @endforeach

            </table>

            <script>
                $('#group_courses_list').DataTable({
                    "searching": false,
                    "lengthChange": false,
                    "columnDefs": [
                        {
                            "targets": [0,1,6],
                            "orderable": false
                        },{
                            "targets": [0],
                            "visible": false
                        },
                        {
                            "width": "40%",
                            "targets": [2]
                        }
                    ],
                    "fixedColumns": true
                });
            </script>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <strong>{{ __('No Results Found') }}</strong>
        <span class="d-block">{{ __('there are none data to show!') }}</span>
    </div>
@endif
