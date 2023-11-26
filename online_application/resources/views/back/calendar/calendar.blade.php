@extends('back.layouts.default')
@section('styles')
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages/core/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages/daygrid/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages/timegrid/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages/list/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages-premium/timeline/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages-premium/resource-timeline/main.css') }}" rel='stylesheet' />
@endsection


@section('scripts')
    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages/core/main.js') }}"></script>

    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages/daygrid/main.js') }}"></script>

    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages/timegrid/main.js') }}"></script>

    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages-premium/resource-common/main.js') }}"></script>

    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages-premium/resource-daygrid/main.js') }}"></script>

    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages-premium/resource-timegrid/main.js') }}"></script>


    <script>
        url_classrooms = '{{route('classroomSlots.classrooms')}}';
        url_classroomSlots = '{{route('classroomSlots.classroomSlots')}}';
        url_lessons = '{{route('lessons.index')}}';
        console.log(url_classroomSlots);
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                plugins: [ 'resourceTimeGrid' ],
                timeZone: 'UTC',
                defaultView: 'resourceTimeGridWeek',
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
                    right:  'addEventButton prev,next,today  resourceTimeGridDay,resourceTimeGridWeek'
                },
                views: {
                    resourceTimeGridMonthDay: {
                        type: 'resourceTimeGrid',
                        duration: { days: 30 },
                        buttonText: 'Month'
                    }
                },
                customButtons: {
                    addEventButton: {
                        text: 'Add Lesson',
                        click: function() {
                            app.lessonCreate("{{route('lessons.create')}}","","data-group-id");
                        }
                    }
                },
                resources: { // you can also specify a plain string like 'json/resources.json'
                    url: url_classrooms,
                    failure: function () {
                        document.getElementById('script-warning').style.display = 'block';
                    }
                },
                eventRender: function (info) {
                    console.log(info);
                    console.log(info.event);
                    console.log(moment().format('LT'));
                    var element = $(info.el);
                    element.css({
                        'backgroundColor': `{{ isset($settings['calendar']['calendar_event_color']) ? $settings['calendar']['calendar_event_color'] : '#2a77a6'  }}`,
                        'borderColor': `{{ isset($settings['calendar']['calendar_event_color']) ? $settings['calendar']['calendar_event_color'] : '#2a77a6'  }}`,
                        'color': '#fff'
                    });
                    var html = '<div><strong>Lesson: </strong></div>';
                    html += '<div><strong>Start:</strong> ' + moment(info.event.start).format('LT') + '</div>';
                    html += '<div><strong>End:</strong> ' + moment(info.event.end).format('LT') + '</div>';
                    html += '<div><strong>Course:</strong> <br>' + info.event.extendedProps.course + '</div>';
                    element.html(html);

                },
                eventClick: function(info) {
                    var eventObj = info.event;

                    window.open(url_lessons + '/' + eventObj.id, '_self');
                },
                events: { // you can also specify a plain string like 'json/events-for-resources.json'
                    url: url_classroomSlots,
                    failure: function () {
                        document.getElementById('script-warning').style.display = 'block';
                    }
                }
            });

            calendar.render();
        });

        function resourcesCampus(campus_element, url) {
            var resources= calendar.getResources();
            resources.forEach(function(el){
                el.remove();
            });

            campus_id = $(campus_element).val();
            var classroom = $('[name="classroom"]');

            classroom.empty();

            $.ajax({
                url: url,
                data: {campus: campus_id}
            }).done(function(data) {
                if(data.length > 0) {
                    classroom.append(new Option('All Classrooms', ''));
                }
                data.forEach(function(el){
                    calendar.addResource(el);
                    classroom.append(new Option(el.title, el.id));
                });
            });
        }

        function resourcesClassroom(classroom_element, url) {
            var resources= calendar.getResources();
            resources.forEach(function(el){
                el.remove();
            });

            classroom_id = $(classroom_element).val();
            console.log(url);
            $.ajax({
                url: url,
                data: {classroom: classroom_id}
            }).done(function(data) {

                data.forEach(function(el){
                    calendar.addResource(el);
                });
            });
        }

    </script>
@endsection

@section('content')
    <style>
        .fc-event div:first-of-type {
            font-size: 1.1rem !important;
            padding-bottom: 5px !important;
        }
        .fc-event  div:not(:first-of-type) {
            font-size: 1.0rem;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-3">
                                @include('back.layouts.core.forms.select', [
                                    'name'          => 'campus',
                                    'label'         => 'Campus' ,
                                    'class'         => 'select2',
                                    'required'      => false,
                                    'attr'          => "onchange=resourcesCampus(this,'".route('classroomSlots.classrooms')."')",
                                    'value'         => '',
                                    'placeholder'   => 'All Campuses',
                                    'data'          => \App\Helpers\School\CampusHelpers::getCampusesInArrayOnlyTitleId($campuses)
                                ])
                            </div>

                            <div class="col-3">
                                @include('back.layouts.core.forms.select', [
                                    'name'          => 'classroom',
                                    'label'         => 'Classroom' ,
                                    'class'         => 'select2',
                                    'required'      => false,
                                    'attr'          => "onchange=resourcesClassroom(this,'".route('classroomSlots.classrooms')."')",
                                    'value'         => '',
                                    'placeholder'   => 'All Classrooms',
                                    'data'          => \App\Helpers\School\ClassroomHelpers::getClassroomsInArrayOnlyTitleId()
                                ])
                            </div>
                        </div>
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection