<div class="card">

    <div class="card-body">

        <div class="d-md-flex align-items-center">
            <div>
                <h4 class="card-title">{{__('Quotations')}}</h4>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table no-wrap v-middle">

                <thead class="bg-info text-white">

                <tr class="border-0">
                    <th class="border-0">{{__('Quotation')}}</th>
                    <th class="border-0">{{__('Price')}}</th>
                    <th class="border-0"></th>
                    <th class="border-0"></th>
                </tr>

                </thead>

                <tbody>
                @foreach ($bookings as $booking)

                    <tr data-booking-id="{{$booking->id}}">

                        <td>
                            <h6 class="text-info">{{$booking->quotation->title}}</h6>
                            <small class="d-block">
                                <span class="d-block text-muted"><strong>{{__('Programs')}}:</strong></span>
                                @if (isset($booking->invoice['details']['courses']))
                                    @foreach ($booking->invoice['details']['courses'] as $course)

                                        <span class="d-block text-info">
                                            {{$course['title']}} - {{$course['campus_title']}}
                                        </span>
                                        @foreach ($course['dates'] as $date)
                                            <strong class="d-block text-muted" style="display:block">
                                                {{ QuotationHelpers::formateStartEndDates($date['start'].':'.$date['end']) }}
                                                <span>(@price({{number_format($date['price'])}}))</span>
                                            </strong>
                                        @endforeach
                                    @endforeach
                                @endif

                            </small>
                            <small class="d-block">{{ $booking->created_at->diffForHumans() }} </small>

                        </td>


                        <td>
                            @price({{number_format($booking->invoice['totalPrice'])}}) {{__('per student')}}

                        </td>
                        <td>
                            <button type="button" data-application="{{$booking->quotation->application->id}}"
                                    data-booking="{{$booking->id}}" class="btn btn-success"
                                    onclick="app.addStudent('{{route('school.agent.booking.student.create' , $school)}}' , ' ' , 'Book for a student' , this )">
                                <i class="fa fa-plus"></i> {{__('Book for a student')}}</button>
                        </td>


                        <td>
                            <a href="javascript:void(0)" class="btn btn-sm btn-light btn-info"
                               data-toggle="tooltip" data-placement="top" title="{{__('View Booking Details')}}"
                               onclick="app.getBookingDetails( '{{route('booking.show' , ['school'=> $school , 'booking'=> $booking ])}}' , {{ $booking->id }})">
                                <span class="ti-file"></span> {{__('Details')}}
                            </a>

                            <a href="{{route('quotations.show' , ['quotation' => $booking->quotation , 'school' => $school])}}"
                               class="btn btn-sm btn-light btn-info">
                                <span class="ti-reload"></span> {{__('Get New Price')}}
                            </a>

                            {{--  <a href="javascript:void(0)"
                            class="btn btn-sm btn-light btn-info text-danger" onclick="app.deleteBooking(this)"
                            data-route="{{route('booking.destroy' , ['school' => $school , 'booking' => $booking ])}}"
                            data-toggle="tooltip" data-placement="top" title="{{__('Delete Booking')}}"
                            data-application="{{$booking->quotation->application->id}}"
                            data-booking ="{{$booking->id}}"><span class="ti-trash"></span></a>  --}}

                        </td>
                    </tr>

                @endforeach

                </tbody>

                <tfoot>

                <tr>
                    <td colspan="4">
                        <div class="d-flex justify-content-center">
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>