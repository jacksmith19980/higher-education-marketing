<div class="card">
    <div class="card-body">
        <div class="d-md-flex align-items-center">
            <div>
                <h4 class="card-title">{{__('Bookings')}}</h4>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table no-wrap v-middle">
                <thead class="bg-info text-white">
                    <tr class="border-0">
                        <th class="border-0">{{__('Booking')}}</th>
                        <th class="border-0">{{__('Price')}}</th>
                        <th class="border-0"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)

                    @php
                        if($submission = $booking->submissions->last()){
                            $invoiceStatus = ( isset($submission->invoice) ) ?$submission->invoice->lastStatus()->status : 'N/A';
                        }else{
                            $invoiceStatus = 'N/A';
                        }
                    @endphp

                    <tr>
                        <td>

                            <h6 class="text-info">{{$booking->quotation->title}}</h6>

                            <small class="d-block">

                                <span class="d-block text-muted"><strong>Programs:</strong></span>
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
                        @if(
                            isset($booking->invoice['details']['price']['total_before_discount']) &&
                            $booking->invoice['details']['price']['total_before_discount'] > 0
                        )
                            <td>

                                <del>@price({{ sprintf ('%.2n', $booking->invoice['details']['price']['total_before_discount']) }})</del> -
                                @price({{ $booking->invoice['totalPrice'] }})

                            </td>
                        @else
                            <td>
                                @price({{ $booking->invoice['totalPrice'] }})

                            </td>
                        @endif
                        <td style="text-align: right">

                            {{-- in the user has already filled the booking application --}}

                            @if($userApplication->count() &&
                                    in_array( $booking->quotation->application->id , $userApplication->pluck('id')->toArray() )
                                    && $invoiceStatus != 'Paid'
                            )

                            <a class="btn btn-success btn-sm mr-2 ml-2"
                                href="{{ route( 'application.show' ,
                                                [
                                                'school'        => $school ,
                                                'application'   => $booking->quotation->application,
                                                'booking'       => $booking->id
                                                ]
                                                )}}"
                                >
                                {{__('Complete your booking')}}
                            </a>


                            @elseif($invoiceStatus != 'Paid')

                            <a class="btn btn-success btn-sm mr-2 ml-2"
                                href="{{ route( 'application.show' ,
                                                [
                                                'school'        => $school ,
                                                'application'   => $booking->quotation->application,
                                                'booking'       => $booking->id
                                                ]
                                                )}}"
                                >
                                <i class="fa fa-plus"></i>
                                {{__('Book Now')}}
                            </a>

                            @else
                                @if($submission)
                                    <a class="btn btn-success btn-sm mr-2 ml-2"
                                    href="{{ route( 'school.submissions.show.review' ,[
                                                'school'        => $school,
                                                'submission'    => $submission,
                                            ]
                                        )}}">
                                    <i class="fa fa-eye"></i>
                                    {{__('View Booking')}}
                                    </a>
                                @endif

                            @endif

                            <a href="javascript:void(0)" class="btn btn-sm btn-light btn-info mr-2 ml-2" data-toggle="tooltip"
                                data-placement="top" title="{{__('View Booking Details')}}"
                                onclick="app.getBookingDetails( '{{route('booking.show' , ['school'=> $school , 'booking'=> $booking ])}}' , {{ $booking->id }})">
                                <span class="ti-file"></span> {{__('Details')}}
                            </a>

                            {{-- <a href="{{route('quotations.show' , ['quotation' => $booking->quotation , 'school' => $school])}}"
                                class="btn btn-sm btn-light btn-info mr-2 ml-2">
                                <span class="ti-reload"></span> {{__('Get New Price')}}
                            </a> --}}

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
