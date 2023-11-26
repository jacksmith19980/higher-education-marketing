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
                        <th class="border-0"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                    <tr>
                        <td>
                            {{$booking->quotation->title}}
                        </td>

                        <td>
                            {{ number_format($booking->invoice['totalPrice']) }} {{$settings['school']['default_currency']}}
                        </td>
                        
                        <td>
                            <button type="button" data-application="{{$booking->quotation->application->id}}" data-booking ="{{$booking->id}}" class="btn btn-success" onclick="app.addStudent('{{route('school.parent.child.create' , $school)}}' , ' ' , 'Add Child' , this )"><i class="fa fa-plus"></i> {{__('Book for a child')}}</button>
                        </td>

                        <td>
                            <a href="javascript:void(0)" class="btn btn-light btn-info" 
                            data-toggle="tooltip" data-placement="top" title="{{__('View Booking Details')}}"
                            onclick="app.getBookingDetails( '{{route('booking.show' , ['school'=> $school , 'booking'=> $booking ])}}' , {{ $booking->id }})">
                                <span class="ti-file"></span>  {{__('Details')}}
                            </a>
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