document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    console.log(url_classrooms);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'resourceTimeline' ],
        now: '{{\Carbon\Carbon::now()}}',
        editable: true, // enable draggable events
        aspectRatio: 1.8,
        scrollTime: '00:00', // undo default 6am scrollTime
        header: {
            left: 'today prev,next',
            center: 'title',
            right: 'resourceTimelineDay,resourceTimelineThreeDays,timeGridWeek,dayGridMonth'
        },
        defaultView: 'resourceTimelineDay',
        views: {
            resourceTimelineThreeDays: {
                type: 'resourceTimeline',
                duration: { days: 3 },
                buttonText: '3 days'
            }
        },
        resourceLabelText: 'Rooms',

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