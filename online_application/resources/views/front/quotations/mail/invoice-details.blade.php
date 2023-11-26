<div class="card-body">
    <h3 class="card-title">{{__('Programs')}}</h3>
    @foreach ($booking->invoice['courses_addons']['courses'] as $course)
    <div class="col-md-12 row">
        <div class="card d-block w-100 bg-light-grey">
            <div class="card-header bg-info text-white">
                <h4 class="m-b-0">{{$course['course']}} - <small>( {{$course['totalWeeks']}}
                        {{Str::plural('Week' , $course['totalWeeks'])}} )</small></h4>
            </div>
            <div class="card-body p-20">
                <table class="table table-striped table-border">
                    <thead>
                        <th>
                            <h5>{{__('From')}}</h5>
                        </th>
                        <th>
                            <h5>{{__('To')}}</h5>
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($course['selectedDates'] as $date)
                        <tr>
                            @php
                            $dates = explode(":" , $date);
                            @endphp

                            @foreach($dates as $date)
                            <td>{{date('l jS \of F Y' , strtotime($date))}}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                @if (isset($course['addons']) && !empty($course['addons']))
                <table class="table table-striped">
                    <thead>
                        <th>
                            <h5>{{__('Activity')}}</h5>
                        </th>
                        <th>
                            <h5>{{__('Price')}}</h5>
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($course['addons'] as $addon)
                        <tr>
                            <td>
                                <h5>{{ $addon['title']}} <small>( {{$addon['week'] }}
                                        {{Str::plural('Week' , $addon['week'])}} ) </small></h5>
                            </td>
                            <td>
                                <strong
                                    class="text-info price">{{$settings['school']['default_currency']}}{{number_format($addon['price'])}}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

    </div>





    @endforeach



    <div class="card-footer">



        <h4 class="float-right text-info price">

            <small>{{__('Programs & Activities')}}</small><br />

            {{__('Sub-total')}}:
            {{$settings['school']['default_currency']}}{{$booking->invoice['courses_addons']['totalCoursesPrice']}}

        </h4>



        <div class="clearfix"></div>

    </div>





    <hr>



</div>







@if (isset($booking->invoice['accommodation']['accommodations']) &&
!empty($booking->invoice['accommodation']['accommodations']))

<div class="card">

    <div class="card-body p-20">



        <h3 class="card-title">{{__('Accommodation')}}</h3>



        <div class="bg-light-grey">



            <div class="list-group d-block w-100">

                <table class="table table-striped">

                    <thead>

                        <th>
                            <h5>{{__('Option')}}</h5>
                        </th>

                        <th>
                            <h5>{{__('Price')}}</h5>
                        </th>

                    </thead>



                    <tbody>



                        @foreach ( $booking->invoice['accommodation']['accommodations'] as $accommodation)



                        <tr>

                            <td>

                                <h6 class="mb-1 text-dark"> {{$accommodation['accommodationTitle']}}</h6>

                            </td>



                            <td>

                                <strong
                                    class="text-info price">{{$settings['school']['default_currency']}}{{number_format($accommodation['accommodationPrice'])}}</strong>

                            </td>



                        </tr>



                        @endforeach

                    </tbody>



                    <tfoot>

                        <tr>

                            <td>
                                <h6>{{__('Sub-Total')}}</h6>
                            </td>

                            <td>

                                <h4 class="float-right text-info price">

                                    <small>{{__('Accommodation')}}</small><br />

                                    {{__('Total')}}:
                                    {{$settings['school']['default_currency']}}{{$booking->invoice['accommodation']['accommodationPrice']}}
                                </h4>

                            </td>

                        </tr>



                    </tfoot>

                </table>



            </div>

        </div>

    </div>

</div>



@endif











@if (isset($booking->invoice['transfer']['transfers']) && !empty($booking->invoice['transfer']['transfers']))



<div class="card">

    <div class="card-body p-20">

        <h3 class="card-title">{{__('Transfers')}}</h3>

        <div class="bg-light-grey">

            <div class="list-group d-block w-100">



                <table class="table table-striped">

                    <thead>

                        <th>
                            <h5>{{__('Option')}}</h5>
                        </th>

                        <th>
                            <h5>{{__('Price')}}</h5>
                        </th>

                    </thead>

                    <tbody>

                        @foreach ( $booking->invoice['transfer']['transfers'] as $transfer)



                        <tr>

                            <td>

                                <h6 class="mb-1 text-dark">{{$transfer['transferTitle']}}</h6>

                            </td>



                            <td>

                                <strong
                                    class="text-info price">{{$settings['school']['default_currency']}}{{number_format($transfer['transferPrice'])}}</strong>

                            </td>



                        </tr>



                        @endforeach

                    </tbody>

                    <tfoot>

                        <tr>

                            <td>
                                <h6>{{__('Sub-Total')}}</h6>
                            </td>

                            <td>

                                <h4 class="float-right text-info price">

                                    <small>{{__('Transfer')}}</small><br />

                                    {{__('Total')}}:
                                    {{$settings['school']['default_currency']}}{{$booking->invoice['transfer']['transferPrice']}}
                                </h4>

                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endif



<div class="alert bg-default row">



    <div class="col-md-12 clearfix">



        <h3 class="float-right text-success m-b-0 p-b-0"><small>{{__('YOUR PRICE:')}}</small>
            {{$settings['school']['default_currency']}}{{ number_format($booking->invoice['totalPrice'])}}<small>{{__('/child')}}</small>
        </h3>



    </div>



</div>