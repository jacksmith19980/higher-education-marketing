@extends('front.layouts.new-instructors')

@section('styles')
<style>
    .fc-event{
        cursor: pointer;
    }
    .nav li a {
        display: block;
        background: #f6f6f6;
        text-decoration: none;
        color: #151515;
        padding: 10px 30px 10px 30px;
        box-shadow: rgb(15 15 15 / 5%) 0px 0px 10px 1px;
    }
    .nav li a.active {
        background: #ffffff!important;
        border-bottom: none!important;
        color: #2d5c7a;
    }
    li:has(> a.active) {
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
</style>
@endsection

@section('content')

    <input id="school_name" name="school_name" type="hidden" value="{{request()->tenant()->slug}}">
    <input id="attendance_encrypted" type="hidden" value="{{Crypt::encrypt('attendance')}}">
    <input id="student_encrypted" type="hidden" value="{{Crypt::encrypt('students')}}">
    <input id="courses_select" name="courses_select" type="hidden" value="{{$course->id}}">

    @include('front.instructor._partials.course-info')

    <div class="row justify-content-center">

        <div class="col-12">

            <ul class="nav nav-pills custom-pills" id="pills-tab"  style="margin-bottom: -5px!important;" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $place ==  'students' ? 'active' : ''}}" id="course-students-tab" data-toggle="pill" href="#students" role="tab" aria-controls="pills-timeline" aria-selected="true">{{__('Students')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $place ==  'calendar' ? 'active' : ''}}" id="course-calendar-tab" data-toggle="pill" href="#calendar" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Calendar')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $place ==  'attendance' ? 'active' : ''}}" id="course-attendances-tab" data-toggle="pill" href="#attendance" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Lessons')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $place ==  'grades' ? 'active' : ''}}" id="course-grades-tab" data-toggle="pill" href="#grades" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Grades')}}</a>
                </li>
            </ul>
        </div>

        <div class="col-12">
            <div class="card" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px;">
                <div class="card-body" style="padding-left: 0!important; padding-right: 0!important;">
                    <div class="tab-content tabcontent-border" >
                        <div class="tab-pane {{ $place ==  'students' ? 'active' : ''}}" id="students" role="tabpanel" style="margin-top: 20px">
                            @include('front.instructor._partials.components.course.students')
                        </div>
                        <div class="tab-pane {{ $place ==  'calendar' ? 'active' : ''}}" id="calendar" role="tabpanel">
                            @include('front.instructor._partials.components.course.calendar')
                        </div>
                        <div class="tab-pane {{ $place ==  'attendance' ? 'active' : ''}}" id="attendance" role="tabpanel" style="margin-top: 20px">
                            @if(isset($lesson)&& !is_null($lesson))
                                <!-- 
                                    @ include('front.instructor._partials.components.lesson.lesson-detail')
                                -->
                                @include('front.instructor._partials.components.lesson.add-attendances')
                            @else
                                @include('front.instructor._partials.components.course.attendances')
                            @endif
                        </div>
                        <div class="tab-pane {{ $place ==  'grades' ? 'active' : ''}}" id="grades" role="tabpanel" style="margin-top: 20px">
                            @include('front.instructor._partials.components.course.grades')
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
<script>
    function get_student_detail(student, course_id, student_place) {
        var school_slug = document.getElementById('school_name').value;
        url_student = '{{route('instructor.student.show', ['school' => 'school_slug', 'student' => 'student_id', 'course' => 'course_id', 'place' => 'student_place' ])}}';
        url_student = url_student.replace('school_slug', school_slug);
        url_student = url_student.replace('student_id', student);
        url_student = url_student.replace('course_id', course_id);
        url_student = url_student.replace('student_place', student_place);
        console.log(url_student);
        var l = window.location;
        $.ajax({
            url: url_student,
            success: function(student) {
                $('#students').html(student);
            }
        });
    }
    function get_lesson_attendances(lesson_id, course_id, type) {
        var school_slug = document.getElementById('school_name').value;
        url_lesson_attendances = '{{route('instructor.lesson.attendances', ['school' => 'school_slug', 'lesson' => 'lesson_id', 'action' => 'action_type'])}}';
        url_lesson_attendances = url_lesson_attendances.replace('school_slug', school_slug);
        url_lesson_attendances = url_lesson_attendances.replace('lesson_id', lesson_id);
        url_lesson_attendances = url_lesson_attendances.replace('action_type', type);
        console.log(url_lesson_attendances);
        var l = window.location;
        $.ajax({
            url: url_lesson_attendances,
            success: function(lesson) {
                $('#attendance').html(lesson);
            }
        });
    }
    function export_lesson_attendances(lesson_id, course_id, type) {
        var school_slug = document.getElementById('school_name').value;
        url_lesson_attendances = '{{route('instructor.lesson.attendances', ['school' => 'school_slug', 'lesson' => 'lesson_id', 'action' => 'action_type'])}}';
        url_lesson_attendances = url_lesson_attendances.replace('school_slug', school_slug);
        url_lesson_attendances = url_lesson_attendances.replace('lesson_id', lesson_id);
        url_lesson_attendances = url_lesson_attendances.replace('action_type', type);
        console.log(url_lesson_attendances);
        var l = window.location;
        app.redirect(url_lesson_attendances);
    }
    function changeCourse() {
        var school_slug = document.getElementById('school_name').value;
        var select_courses = document.getElementById('course_changed');
        var old_selected_course = document.getElementById('courses_select').value;
        var selected_course = select_courses.options[select_courses.selectedIndex].value;
        if(selected_course && old_selected_course != selected_course) {
            url_course_change = '{{route('instructor.course.show', ['school' => 'school_slug', 'course' => 'selected_course', 'place' => 'eyJpdiI6ImlnVUZjOWNURUdnS2JBUkxtZVFodFE9PSIsInZhbHVlIjoic0JTekJvY1RVTmEveE1HTUVra0I3QT09IiwibWFjIjoiZDI3ZDcwNWMxYjgxODYxZjEyZTY1ZTExMjZmYmM2ODBlMjRmNWZkNmRhZjZiMGQ3MWE4ZmM3ZmVhZjg5YWJkZiIsInRhZyI6IiJ9'])}}';
            url_course_change = url_course_change.replace('school_slug', school_slug);
            url_course_change = url_course_change.replace('selected_course', selected_course);
            location.href = url_course_change;
        }
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
    function get_back_to_all_students(course_id)
    {
        var students_url_crypted = document.getElementById('student_encrypted').value;
        var school_slug = document.getElementById('school_name').value;
        url_course_students = '{{route('instructor.course.show', ['school' => 'school_slug', 'course' => 'course_id', 'place' => 'student_place' ])}}';
        url_course_students = url_course_students.replace('school_slug', school_slug);
        url_course_students = url_course_students.replace('course_id', course_id);
        url_course_students = url_course_students.replace('student_place', students_url_crypted);
        location.href = url_course_students;
    }
    function get_back_to_all_attendances(course_id)
    {
        var attendances_url_crypted = document.getElementById('attendance_encrypted').value;
        var school_slug = document.getElementById('school_name').value;
        url_course_students = '{{route('instructor.course.show', ['school' => 'school_slug', 'course' => 'course_id', 'place' => 'attendance_place' ])}}';
        url_course_students = url_course_students.replace('school_slug', school_slug);
        url_course_students = url_course_students.replace('course_id', course_id);
        url_course_students = url_course_students.replace('attendance_place', attendances_url_crypted);
        location.href = url_course_students;
    }
</script>
<script>
    var school_slug = document.getElementById('school_name').value;
    var attendances_url_crypted = document.getElementById('attendance_encrypted').value;
    var selected_course = document.getElementById('courses_select').value;
    url_lessons = '{{route('instructor.lessons', ['school' => 'school_slug', 'course' => 'selected_course'])}}';
    url_lessons = url_lessons.replace('school_slug', school_slug);
    url_lessons = url_lessons.replace('selected_course', selected_course);

    url_course = '{{route('instructor.course.show', ['school' => 'school_slug', 'course' => 'course_id'])}}';
    url_course = url_course.replace('school_slug', school_slug);

    document.addEventListener('DOMContentLoaded', function() {
        var calendarC = document.getElementById('ccalendar');
        calendarC = new FullCalendar.Calendar(calendarC, {
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            plugins: [ 'dayGrid'],
            defaultView: 'dayGridMonth',
            now: '{{\Carbon\Carbon::now()}}',
            aspectRatio: 1.8,
            themeSystem: 'bootstrap',
            slotDuration: '00:15',
            minTime:"08:00",
            maxTime:"22:00",
            resourceLabelText: 'Rooms',
            datesAboveResources: true,
            header: {
                left:   'title',
                center: '',
                right:  ' prev,next,today  dayGridMonth,dayGridWeek,dayGridDay'
            },
            resources: { // you can also specify a plain string like 'json/resources.json'
                url: url_lessons,
                failure: function () {
                    document.getElementById('script-warning').style.display = 'block';
                }
            },
            eventRender: function (info) {
                    var element = $(info.el);
                    element.css({
                        'backgroundColor': `{{ isset($settings['calendar']['calendar_event_color']) ? $settings['calendar']['calendar_event_color'] : '#2a77a6'  }}`,
                        'borderColor': `{{ isset($settings['calendar']['calendar_event_color']) ? $settings['calendar']['calendar_event_color'] : '#2a77a6'  }}`,
                        'color': '#fff'
                    });
                    var html = '<div><strong>' + info.event.extendedProps.course + '</strong></div>';
                    html += '<div>' + moment(info.event.start).format('LT') + ' - ' + moment(info.event.end).format('LT') + '</div>';
                    element.html(html);

            },
            eventClick: function(info) {
                var eventObj = info.event;
                url_course = url_course.replace('course_id', eventObj.extendedProps.courseId);
                window.open(url_course + '/'+attendances_url_crypted+'/' + eventObj.id , '_self');
            },
            events: { // you can also specify a plain string like 'json/events-for-resources.json'
                url: url_lessons,
                failure: function () {
                    document.getElementById('script-warning').style.display = 'block';
                }
            }
        });
        calendarC.render();
        calendarC.setOption('height', 700);
    });
</script>
<script>
$(document).ready( function () {
    $('#course_students').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [{
            "targets": 4,
            "orderable": false
        }]
    });
    $('#course_attendances').DataTable({
        "searching": false,
        "lengthChange": false,
        "columnDefs": [{
            "targets": 5,
            "orderable": false
        }]
    });
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
    });
</script>
@endsection
