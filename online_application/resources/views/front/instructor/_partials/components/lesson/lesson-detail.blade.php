<div>
    <div style="float:right; margin-right: 5%">
        <a href="javascript:get_back_to_all_attendances({{$course->id}});">
            &larr; {{__('BACK TO ATTENDANCE')}}
        </a>
    </div>
    <div style="margin-left: 2%">
        <table>
            <tr>
                <td>{{__('DATE')}}:</td>
                <td style="padding-left: 30px">{{$lesson->date}}</td>
            </tr>
            <tr>
                <td>{{__('CLASSROOM')}}:</td>
                <td style="padding-left: 30px">{{$lesson->classroom->title}}</td>
            </tr>
            <tr>
                <td>{{__('START TIME')}}:</td>
                <td style="padding-left: 30px">{{date('h:i A', strtotime($lesson->classroomSlot->start_time))}}</td>
            </tr>
            <tr>
                <td>{{__('END TIME')}}:</td>
                <td style="padding-left: 30px">{{date('h:i A', strtotime($lesson->classroomSlot->end_time))}}</td>
            </tr>
        </table>
    </div>
</div>

<div class="col-12" style="padding-top: 3%">

    @if ( $attendances_count = $lesson->attendances->count() )
    <div class="">
        <table id="lesson_attendances" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
            <thead>
                <tr>
                    <th></th>
                    <th class="all no-sort text-left" style="width: 115px!important">
                        <div class="btn-group" style="display: inline-flex!important;">
                            <div class="btn btn-light">
                                <input type="checkbox" id="select_all_attendances" name="select_all" value="" onchange="app.toggleSelectAll(this)"/>
                            </div>
                            <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split toggle-bulk-actions"
                                    id="toggle-bulk-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item bulk-action-item" href="javascript:void(0)"
                                    onclick="app.bulkEdit('{{route('attendances.bulk.edit')}}' , 'Edit Attendances')">
                                    <i class="fas fa-pencil-alt mr-1"></i> {{__('Edit Selected')}}
                                </a>
                            </div>
                        </div>
                    </th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Status')}}</th>
                    <th class="all no-sort text-right"></th>
                </tr>
            </thead>
            @foreach($lesson->attendances as $attendance)
                <tr>
                    <td></td>
                    <td class="text-left" style="padding-left: 39px;">
                        <input style="width: 15px; height: 15px;" type="checkbox" name="multi-select" class="select_attendance" value="{{$attendance->id}}" onchange="checkSelectAll()">
                    </td>
                    <td>{{$attendance->student->name}}</td>
                    <td><span class="badge badge-secondary" style="font-size: 12px">{{$attendance->status}}</span></td>
                    <td class="text-right">
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
                                <i class="icon-pencil"></i><span class="pl-2 icon-text">{{__('Edit')}}</span>
                            </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    @else
        <div class="alert alert-warning">
            <strong>{{__('No Results Found')}}</strong>
            <span class="d-block">{{__('there are none data to show!')}}</span>
        </div>
    @endif

    </div>
