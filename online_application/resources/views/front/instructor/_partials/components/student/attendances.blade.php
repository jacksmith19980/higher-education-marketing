@if ( $attendances_count = $studentAttendances->count() )
    <div class="">
        <table id="student_attendances" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
            <thead>
                <tr>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Classroom')}}</th>
                    <th>{{__('Status')}}</th>
                    <th></th>
                </tr>
            </thead>
            @foreach($studentAttendances as $attendance)
                @continue (!isset($attendance->lesson) || $attendance->lesson == null)
                <tr>
                    <td style="width:30%;">{{$attendance->lesson->date}}</td>
                    <td>{{$attendance->lesson->classroom->title}}</td>
                    <td>
                        <span class="badge badge-secondary">{{ucfirst($attendance->status)}}</span>
                    </td>
                    <td>
                        <div class="btn-group more-optn-group float-right">
                                <button type="button"
                                class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu">

                                <a href="javascript:void(0)"
                                    onclick="app.editAttendance(`{{route('attendances.edit', $attendance)}}`)"
                                    class="dropdown-item" data-toggle="tooltip" data-placement="top"
                                    data-original-title="Edit Attendance">
                                    <i class="icon-pencil"></i><span class="pl-2 icon-text">{{__('Edit')}}</span>
                                </a>
                                </div>
                            </div>

                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <script>
    $('#student_attendances').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [{
            "targets": 4,
            "orderable": false
        }]
    });
    </script>
@else
    <div class="alert alert-warning">
        <strong>{{__('No Results Found')}}</strong>
        <span class="d-block">{{__('there are none data to show!')}}</span>
    </div>
@endif
