@extends('front.coursesCalendar.layouts.layout')
@section('css')
    {{--    <link href="{{ asset('media/css/calendar.css')}}" rel="stylesheet" />--}}
    <link href="{{ asset('media/libs/fullcalendar/packages/core/main.css')}}" rel="stylesheet"/>
    <link href="{{ asset('media/libs/fullcalendar/packages/daygrid/main.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    @include('front.coursesCalendar.includes.calendar-css')

    <style>

        body {
            margin: 40px 10px;
            padding: 0;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
        .status-available{
            display: none;
        }
        .button-status-completed{
            display: none;
        }

        .fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.disabled{
            background-color: #ccc,
            border:1px solid #ccc,
            color: #fff
        }

    </style>

@endsection

@section('content')
    <!-- Modal -->
    <div id="calendarModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalTitle" class="modal-title"></h4>
                </div>
                <div id="modalBody" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div>
        <div id='calendar'></div>
    </div>
@endsection

@section('scripts')
    {{--    <script src="{{ asset('media/libs/jquery/dist/jquery.min.js') }}"></script>--}}
    {{--    <script src="{{ asset('media/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>--}}

    <script src="{{ asset('media/libs/fullcalendar/packages/core/main.js')}}"></script>
    <script src="{{ asset('media/libs/fullcalendar/packages/interaction/main.js')}}"></script>
    <script src="{{ asset('media/libs/fullcalendar/packages/daygrid/main.js')}}"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script>
        var mQuery = jQuery.noConflict(true);
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid'],
                defaultView: 'dayGridMonth',
                eventLimit: false,
                events: {!! $courses_dates !!},
                eventClick: function (event, jsEvent, view) {
                    mQuery('#modalTitle').html(event.event.title);
                    if (event.event.extendedProps.url_read_more !== '/') {
                        var read_more = '<a class="btn btn-primary float-left"  role="button" href="' +
                            event.event.extendedProps.url_read_more +
                            '" target="_blank">Read More</a>';
                    } else {
                        var read_more = '';
                    }

                    mQuery('#modalBody').html(
                        'Start: ' + moment(event.event.start).format('LLL') + '<br />End: ' + moment(event.event.end).format('LLL') + '<br /><br /><br />' +
                        read_more +
                        '<div class="alert alert-danger status-'+ event.event.extendedProps.status +'"><small>{{__('No Available seats for this course')}}</small></div><a class="btn btn-primary float-right button-status-'+ event.event.extendedProps.status +'" disabled="event.event.extendedProps.status"  role="button" href="' + event.event.extendedProps.url_register + '" target="_blank">{{__('Register Now')}}</a>'
                    );
                    mQuery('#calendarModal').modal();
                },
                render: true,
                eventRender: function (info) {

                    var element = mQuery(info.el);

                    if(info.event.extendedProps.status == 'available'){

                        element.css({
                            'backgroundColor': `{{ isset($settings['calendar']['calendar_event_color']) ? $settings['calendar']['calendar_event_color'] : '#2a77a6'  }}`,
                            'borderColor': `{{ isset($settings['calendar']['calendar_event_color']) ? $settings['calendar']['calendar_event_color'] : '#2a77a6'  }}`,
                            'color': '#fff',
                            'cursor': 'pointer',
                        });

                        element.html('<div>' + info.event.title + '</div><div><strong>Start:</strong> ' + moment(info.event.start).format('LT') + '</div><div><strong>End:</strong> ' + moment(info.event.end).format('LT') + '</div>');

                    }else if(info.event.extendedProps.status == 'completed'){

                        element.addClass('disabled');
                        element.css({
                            'backgroundColor': '#ccc',
                            'borderColor': '#ccc',
                            'color': '#fff',
                            'cursor': 'none',
                            'opacity':'0.6'
                        });
                        element.html('<div>' + info.event.title + '</div><div><strong><span>No Available seats for this course</span></div>');

                        element.attr('disabled', 'disabled');

                    }
                }
            });

            calendar.render();
        });

    </script>

    <script src="{{ asset('media/js/calendar.js')}}"></script>


@endsection
