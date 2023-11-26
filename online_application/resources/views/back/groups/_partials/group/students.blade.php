<table id="group_students_list" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
    <thead>
        <tr>
            <th>{{__('Name')}}</th>
            <th>{{__('Cohort')}}</th>
            <th>{{__('Start Date')}}</th>
            <th>{{__('End Date')}}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @if ($students)
            @foreach ($students as $student)
                <tr data-student-id="{{$student->id}}">

                    <td>
                        <a href="javascript:get_group_student({{$student->id}}, {{$group->id}}, 'group_student_attendances')">
                            <span style="font-size: 16px; font-weight: bold;">{{$student->name}}</span>
                        </a>
                        <br>
                        <span style="font-size: 14px">{{$student->email}}</span>
                    </td>
                    <td>
                        <span style="font-size: 12px" class="badge badge-secondary">
                            {{$group->title}}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px" class="badge badge-primary">
                            {{$group->start_date}}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px" class="badge badge-primary">
                            {{$group->end_date}}
                        </span>
                    </td>
                    <td>
                        @php
                        $buttons = [
                            ["text"=>"View", "icon"=>"fas fa-graduation-cap", "class"=>"", "url"=>"javascript:get_group_student($student->id, $group->id, 'group_student_attendances')"],
                            ["text"=>"Grades", "icon"=>"far fa-newspaper", "class"=>"", "url"=>"javascript:get_group_student($student->id, $group->id, 'group_student_grades')"],
                            ["text"=>"Attendance", "icon"=>"fas fa-users", "class"=>"", "url"=>"javascript:get_group_student($student->id, $group->id, 'group_student_attendances')"]
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
            @endforeach
        @endif
    </tbody>
</table>

<script>
    $('#group_students_list').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            {
                "targets": 4,
                "orderable": false
            }
        ]
    });
</script>
