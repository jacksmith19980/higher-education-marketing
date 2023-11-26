@include('front.instructor._partials.components.lesson.lesson-info')

<div style="padding-top: 3%">

<form method="POST" action="{{ route('lesson.update.or.create.attendances', ['lesson' => $lesson]) }}"
    enctype="multipart/form-data">
    @csrf
    <div>
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
                                    onclick="app.bulkEdit('{{route('lesson.attendances.bulk.edit', $lesson)}}' , 'Edit Attendances')">
                                    <i class="fas fa-pencil-alt mr-1"></i> {{__('Edit Selected')}}
                                </a>
                            </div>
                        </div>
                    </th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Status')}}</th>
                </tr>
            </thead>
            @foreach($students as $student)
                @if (isset($lesson))
                    @php
                        $lessonGroups = $lesson->groups()->pluck('lessoneable_id')->toArray();
                    @endphp
                    @continue (!in_array($student->pivot->group_id, $lessonGroups))
                @endif
                <tr>
                    <td></td>
                    <td class="text-left" style="padding-left: 39px;">
                        <input style="width: 15px; height: 15px;" type="checkbox" name="multi-select" class="select_attendance" value="{{$student->id}}" onchange="checkSelectAll()">
                    </td>
                    <td>{{$student->name}}</td>
                    <td>
                        @include('back.layouts.core.forms.select',
                                [
                            'name'          => $student->id,
                            'label'         => false ,
                            'placeholder'   => 'Select Status' ,
                            'class'         =>'' ,
                            'value'         => isset($student->attendance) ? $student->attendance->status : 'présent - classe',
                            'required'      => true,
                            'attr'          => '',
                            'data'          => AttendanceHelpers::getDefaultStatusesList()
                        ])
                    </td>

                </tr>
            @endforeach
        </table>
        <div style="text-align:right;">
            <button style="margin-top: 30px; margin-right: 20px; padding: 10px 25px 10px 25px;" type="submit" class="btn btn-primary">{{__('Save')}}</button>
        </div>
    </div>
</form>

<script>
    $('#lesson_attendances').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [{
            "targets": 0,
            "visible": false,
            "orderable": false
        },{
            "targets": 1,
            "orderable": false
        },{
            "targets": 3,
            "orderable": false
        }]
    });
</script>


</div>
