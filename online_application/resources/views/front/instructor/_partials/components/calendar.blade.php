<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <div class="col-5">
                        @include('back.layouts.core.forms.select', [
                            'name'          => 'courses_select',
                            'label'         => 'Courses' ,
                            'class'         => 'select2',
                            'required'      => false,
                            'attr'          => "onchange=resourceCourseLessons() id=courses_select",
                            'value'         => '',
                            'placeholder'   => 'All Courses',
                            'data'          => Arr::pluck(auth()->guard('instructor')->user()->courses->toArray(), 'title', 'id')
                        ])
                    </div>
                </div>
                <div id='calendar'>
                </div>
            </div>
        </div>
    </div>
</div>

@section('_scripts')
<script>

    var school_slug = document.getElementById('school_name').value;
    var attendances_url_crypted = document.getElementById('attendance_encrypted').value;
    var selected_course = document.getElementById('courses_select').value;
    
    url_lessons = '{{route('instructor.lessons', ['school' => 'school_slug'])}}';
    url_lessons = url_lessons.replace('school_slug', school_slug);

    url_course = '{{route('instructor.course.show', ['school' => 'school_slug', 'course' => 'course_id'])}}';
    url_course = url_course.replace('school_slug', school_slug);
    
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
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

        calendar.render();

    });

    function resourceCourseLessons() {
        console.log("resourceCourseLessons");
        var school_slug = document.getElementById('school_name').value;
        var select_courses = document.getElementById('courses_select');
        var selected_course = select_courses.options[select_courses.selectedIndex].value;
        url_lessons_new = '{{route('instructor.lessons', ['school' => 'school_slug', 'course' => 'selected_course'])}}';
        url_lessons_new = url_lessons_new.replace('school_slug', school_slug);
        url_lessons_new = url_lessons_new.replace('selected_course', selected_course);
        
       $.ajax({
            url: url_lessons_new,
        }).done(function(data) {
            calendar.removeAllEvents();
            data.forEach(function(el){
                calendar.addEvent(el);
            });
        });
    }

</script>
@endsection