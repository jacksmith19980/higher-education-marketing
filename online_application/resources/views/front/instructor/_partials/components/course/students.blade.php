<table id="course_students" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
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
                        <a href="javascript:get_student_detail({{$student->id}}, {{$course->id}}, 'student_attendance')">
                            <span style="font-size: 16px; font-weight: bold;">{{$student->name}}</span>
                        </a>
                        <br>
                        <span style="font-size: 14px">{{$student->email}}</span>
                    </td>
                    <td>
                        <span style="font-size: 12px" class="badge badge-secondary">
                            {{$student->group->title}}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px" class="badge badge-primary">
                            {{$student->group->start_date}}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px" class="badge badge-primary">
                            {{$student->group->end_date}}
                        </span>
                    </td>
                    <td>
                        @php
                        $buttons = [["text"=>"View", "icon"=>"fas fa-graduation-cap", "class"=>"", "url"=>"javascript:get_student_detail($student->id, $course->id, 'student_attendance')"],
                                    ["text"=>"Grades", "icon"=>"far fa-newspaper", "class"=>"", "url"=>"javascript:get_student_detail($student->id, $course->id, 'student_grades')"],
                                    ["text"=>"Attendance", "icon"=>"fas fa-users", "class"=>"", "url"=>"javascript:get_student_detail($student->id, $course->id, 'student_attendance')"]]
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
