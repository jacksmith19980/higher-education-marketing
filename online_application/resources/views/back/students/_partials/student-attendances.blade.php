<div class="p-4 tab-pane fade l-psuedu-border bg-grey-1" id="nav-attendance" role="tabpanel" aria-labelledby="pills-attendances-tab">
        @if ( $attendances_count = $applicant->attendances->count() )
            <div class="">
                <table class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
                    <thead>
                        <tr>
                            <th>{{__('Course')}}</th>
                            <th>{{__('Instructor')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                    </thead>
                    @foreach($applicant->attendances as $attendance)
                        @continue (!isset($attendance->lesson) || $attendance->lesson == null)
                        <tr>
                            <td>{{$attendance->lesson->course->title}}</td>
                            <td>{{$attendance->instructor->name}}</td>
                            <td style="width:30%;">{{$attendance->lesson->date}}</td>
                            <td>
                                <span class="badge badge-secondary">{{ucfirst($attendance->status)}}</span>
                            </td>
                            <td>
                                <div class="btn-group more-optn-group">
                                     <button type="button"
                                        class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     </button>
                                     <div class="dropdown-menu">

                                        <a href="javascript:void(0)"
                                           onclick="app.editAttendance(`{{route('attendances.edit', $attendance)}}`)"
                                           class="dropdown-item" data-toggle="tooltip" data-placement="top"
                                           data-original-title="Edit Attendance">
                                           <i class="icon-pencil"></i><span class="pl-2 icon-text">Edit</span>
                                        </a>
                                     </div>
                                  </div>

                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @else
            @include('back.students._partials.student-no-results')
        @endif
</div>
