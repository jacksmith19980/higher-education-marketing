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
        url_classrooms = '{{route('classroomSlots.classrooms', ['campus' => $campus])}}';
        url_classroomSlots = '{{route('classroomSlots.classroomSlots', ['campus' => $campus])}}';

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                plugins: [ 'resourceTimeGrid' ],
                timeZone: 'UTC',
                defaultView: 'resourceTimeGridDay',
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
                    right:  'addEventButton prev,next,today  resourceTimeGridDay,resourceTimeGridThreeDay,resourceTimeGridWeek'
                },
                views: {
                    resourceTimeGridThreeDay: {
                        type: 'resourceTimeGrid',
                        duration: { days: 3 },
                        buttonText: '3 days'
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
                events: { // you can also specify a plain string like 'json/events-for-resources.json'
                    url: url_classroomSlots,
                    failure: function () {
                        document.getElementById('script-warning').style.display = 'block';
                    }
                }
            });

            calendar.render();
        });

    </script>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection