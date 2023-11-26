@php
    $show_buttons = false;
    $title = $group->title;
@endphp

@extends('back.layouts.default')

@section('styles')
<style>
#li2 a {
    background: #f6f6f6!important;
    color: #2d5c7a!important;
    opacity: 1!important;
}
#li2 a.active {
    background: #2d5c7a!important;
    color: #ffffff!important;  
}
.nav li:not(#li2) a {
    display: block;
    background: #f6f6f6;
    text-decoration: none;
    color: #151515;
    padding: 10px 30px 10px 30px;
    box-shadow: rgb(15 15 15 / 5%) 0px 0px 10px 1px;
}
.nav li:not(#li2) a.active {
    background: #ffffff!important;
    border-bottom: none!important;
    color: #2d5c7a;  
}
li:not(#li2):has(> a.active) {
    border-right:  1px solid #f6f6f6;
    border-left:   1px solid #f6f6f6;
    box-shadow: rgb(15 15 15 / 15%) 0px 0px 10px 1px;
}
@-moz-document url-prefix() {
ul#pills-tab li.nav-item a.active {
    border-right:  1px solid #f6f6f6;
    border-left:   1px solid #f6f6f6;
    box-shadow: rgb(15 15 15 / 15%) 0px 0px 10px 1px;
  }
}
.table-responsive {
    overflow-x: visible!important;
}
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    @include('back.layouts.core.helpers.page-actions')
    <div class="col-12">
        <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
            <li class="nav-item"> 
                <a class="nav-link active" data-toggle="pill" href="#group_students" role="tab" aria-controls="pills-timeline" aria-selected="true">{{__('Students')}}</a>
            </li>
            <li class="nav-item {{ isset($place) && $place ==  'group_courses' ? 'active' : ''}}"> 
                <a class="nav-link" data-toggle="pill" href="#group_courses" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Courses')}}</a>
            </li>
            <li class="nav-item {{ isset($place) && $place ==  'group_attendances' ? 'active' : ''}}"> 
                <a class="nav-link" data-toggle="pill" href="#group_attendances" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Attendances')}}</a>
            </li>
            <li class="nav-item {{ isset($place) && $place ==  'group_grades' ? 'active' : ''}}"> 
                <a class="nav-link" data-toggle="pill" href="#group_grades" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Grades')}}</a>
            </li>
        </ul>
    </div> 
    <div class="col-12">
        <div class="card" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px;">
            <div class="card-body" id="table-card">
                <div class="table-responsive">
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane active" id="group_students" role="tabpanel">
                            @include('back.groups._partials.group.students')
                        </div>
                        <div class="tab-pane p-20 {{ isset($place) && $place ==  'group_courses' ? 'active' : ''}}" id="group_courses" role="tabpanel">
                            @include('back.groups._partials.group.courses')
                        </div>
                        <div class="tab-pane {{ isset($place) && $place ==  'group_attendances' ? 'active' : ''}}" id="group_attendances" role="tabpanel">
                            @include('back.groups._partials.group.attendances')
                        </div>
                        <div class="tab-pane p-20 {{ isset($place) && $place ==  'group_grades' ? 'active' : ''}}" id="group_grades" role="tabpanel">
                            @include('back.groups._partials.group.grades')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
$(document).ready( function () {
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
                "width": "60%", 
                "targets": [2] 
            }
        ],
        "fixedColumns": true
    });
    $('#group_attendances_list').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            {
                "targets": 5,
                "orderable": false
            }
        ]
    });
});
</script>
<script>
    function get_group_student(student_id, group_id, tab_place) 
    {
        url_group_student = '{{route('group.student.show', ['group' => 'group_id', 'student' => 'student_id', 'place' => 'tab_place' ])}}';
        url_group_student = url_group_student.replace('student_id', student_id);
        url_group_student = url_group_student.replace('group_id', group_id);
        url_group_student = url_group_student.replace('tab_place', tab_place);
        var l = window.location;
        $('#group_students').html("<br><br><div class='lds-ripple' style='top: calc(50% - 30.5px)!important;left: calc(50% - 30.5px)!important;'><div class='lds-pos'></div><div class='lds-pos'></div></div><br><br><br><br>");
        $.ajax({
            url: url_group_student,
            success: function(student) {
                $('#group_students').html(student);
            }
        });
    }
    function get_back_to_all_students(group_id)
    {
        url_group_students = '{{route('get.group.students', ['group' => 'group_id'])}}';
        url_group_students = url_group_students.replace('group_id', group_id);
        var l = window.location;
        $('#group_students').html("<br><br><div class='lds-ripple' style='top: calc(50% - 30.5px)!important;left: calc(50% - 30.5px)!important;'><div class='lds-pos'></div><div class='lds-pos'></div></div><br><br><br><br>");
        $.ajax({
            url: url_group_students,
            success: function(students) {
                $('#group_students').html(students);
            }
        });
    }
    function get_group_course(course_id, group_id, tab_place) 
    {
        url_group_course = '{{route('group.course.show', ['group' => 'group_id', 'course' => 'course_id', 'place' => 'tab_place' ])}}';
        url_group_course = url_group_course.replace('course_id', course_id);
        url_group_course = url_group_course.replace('group_id', group_id);
        url_group_course = url_group_course.replace('tab_place', tab_place);
        var l = window.location;
        $('#group_courses').html("<br><br><div class='lds-ripple' style='top: calc(50% - 30.5px)!important;left: calc(50% - 30.5px)!important;'><div class='lds-pos'></div><div class='lds-pos'></div></div><br><br><br><br>");
        $.ajax({
            url: url_group_course,
            success: function(student) {
                $('#group_courses').html(student);
            }
        });
    }
    function get_back_to_all_courses(group_id)
    {
        url_group_courses = '{{route('get.group.courses', ['group' => 'group_id'])}}';
        url_group_courses = url_group_courses.replace('group_id', group_id);
        var l = window.location;
        $('#group_courses').html("<br><br><div class='lds-ripple' style='top: calc(50% - 30.5px)!important;left: calc(50% - 30.5px)!important;'><div class='lds-pos'></div><div class='lds-pos'></div></div><br><br><br><br>");
        $.ajax({
            url: url_group_courses,
            success: function(students) {
                $('#group_courses').html(students);
            }
        });
    }
    function get_group_lesson_attendances(lesson_id, group_id, tab_place) 
    {
        url_group_lesson_attendances = '{{route('group.lesson.show', ['group' => 'group_id', 'lesson' => 'lesson_id', 'place' => 'tab_place' ])}}';
        url_group_lesson_attendances = url_group_lesson_attendances.replace('lesson_id', lesson_id);
        url_group_lesson_attendances = url_group_lesson_attendances.replace('group_id', group_id);
        url_group_lesson_attendances = url_group_lesson_attendances.replace('tab_place', tab_place);
        var l = window.location;
        $('#group_attendances').html("<br><br><div class='lds-ripple' style='top: calc(50% - 30.5px)!important;left: calc(50% - 30.5px)!important;'><div class='lds-pos'></div><div class='lds-pos'></div></div><br><br><br><br>");
        $.ajax({
            url: url_group_lesson_attendances,
            success: function(student) {
                $('#group_attendances').html(student);
            }
        });
    }
    function get_back_to_all_lessons(group_id)
    {
        url_group_attendances = '{{route('get.group.lessons', ['group' => 'group_id'])}}';
        url_group_attendances = url_group_attendances.replace('group_id', group_id);
        var l = window.location;
        $('#group_attendances').html("<br><br><div class='lds-ripple' style='top: calc(50% - 30.5px)!important;left: calc(50% - 30.5px)!important;'><div class='lds-pos'></div><div class='lds-pos'></div></div><br><br><br><br>");
        $.ajax({
            url: url_group_attendances,
            success: function(students) {
                $('#group_attendances').html(students);
            }
        });
    }
    function export_lesson_attendances(lesson_id)
    {
        url_export_lesson_attendances = '{{route('export.lesson.attendances', ['lesson' => 'lesson_id'])}}';
        url_export_lesson_attendances = url_export_lesson_attendances.replace('lesson_id', lesson_id);
        var l = window.location;
        app.redirect(url_export_lesson_attendances);
    }
    function checkSelectAll() {
        var chk_all = document.getElementById('select_all_attendances');
        var bulk_actions = document.getElementById('toggle-bulk-actions');
        var isChecked =  $("[name='multi-select']").is(':checked')
        if (isChecked) {
            chk_all.checked = true;
            bulk_actions.disabled = false;
        } else {
            chk_all.checked = false;
            bulk_actions.disabled = true;
        }
       
    }
    function open_course_quote_builder(course_id)
    {
        url_course_quote_builder = '{{route('courses.show', ['course' => 'course_id'])}}';
        url_course_quote_builder = url_course_quote_builder.replace('course_id', course_id);
        window.open(url_course_quote_builder, '_blank');
    }
</script>
@endsection