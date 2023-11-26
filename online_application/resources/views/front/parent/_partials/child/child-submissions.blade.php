@if (count($child->submissions))

    <div class="table-responsive">

        <table class="table no-wrap v-middle">

            <thead class="bg-light text-dark">

            <tr>
                <th>{{__('Booking')}}</th>
                <th>{{__('Status')}}</th>
                <th>{{__('Update Date')}}</th>
                <th></th>
            </tr>

            </thead>

            <tbody class="students-table">

            @foreach ($child->submissions as $submission)
                @php
                    $invoice = $submission->booking->invoices()->where('student_id' , $child->id)->with('status')->first();
                @endphp
                <tr>
                    <td>
                        <a href="{{route('school.parent.child.submit' , ['school' => $school , 'application' => $submission->application , 'student' => $child , 'booking' => $submission->booking_id ])}}">
                            {{$submission->application->title}}
                        </a>
                    </td>
                    @if (!isset($invoice))
                        <td>{!! SubmissionHelpers::getSubmitionStatus($submission->status) !!}</td>
                        <td>{{__('N/A')}}</td>

                    @else
                        @if ( $invoice->isPaid )
                            <td>{!! SubmissionHelpers::getSubmitionStatus($submission->status) !!}</td>
                            <td><span class='badge badge-pill badge-success'>{{__('Paid')}}</span></td>
                        @else
                            <td>{!! SubmissionHelpers::getSubmitionStatus('Updated') !!}</td>
                            @php
                                $route = route( 'invoice.pay' , ['school' => $school , 'invoice' => $invoice ]);
                            @endphp
                            <td><a href="{{$route}}" class="btn btn-success btn-sm"
                                   target="_blank">{{__('Pay Now')}}</a></td>
                        @endif
                    @endif

                    <td>
                        <a href="javascript:void(0)" class="btn btn-sm btn-light btn-info"
                           data-toggle="tooltip" data-placement="top" title="{{__('View Booking Details')}}"
                           onclick="app.getBookingDetails( '{{route('booking.show' , ['school'=> $school , 'booking'=> $submission->booking ])}}' , {{ $submission->booking_id }})"><span
                                    class="ti-file"></span> {{__('Details')}}</a>
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

@else

    <div class="alert alert-warning">{{__('No Bookings for '.$child->first_name.', Please click on "Book for a child" and fill the application')}}</div>

@endif