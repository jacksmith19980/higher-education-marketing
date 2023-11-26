{{--  @dump($booking->invoice)  --}}
<div class="card-body">
    <h3 class="card-title">{{__('Courses and Add-ons')}}</h3>

    @foreach ($booking->invoice['details']['courses'] as $course)
    <div class="col-md-12 row">
        <div class="card d-block w-100 bg-light-grey">
            <div class="card-header bg-info text-white">
                <h4 class="m-b-0">{{$course['title']}} - <small>( {{$course['campus_title']}} )</small></h4>
            </div>
            <div class="card-body p-20">
                <table class="table table-striped table-border">
                    @foreach ($course['dates'] as $date)
                    <tr>
                        <td>
                            <h4>
                                {{ QuotationHelpers::formateStartEndDates($date['start'].':'.$date['end']) }}
                                <small>(@price({{number_format($date['price'])}}))</small>
                            </h4>
                            @if (isset($date['addons']))
                            <strong class="d-block mt-1">{{__('Add-ons')}}</strong>
                                @foreach ($date['addons'] as $group=> $addons)
                                    @if (count(array_filter($addons)))
                                        <strong>{{QuotationHelpers::addonsTitle($group)}}</strong>
                                        @foreach ($addons as $addon)
                                            <span class="text-info d-block">
                                                {{$addon['title']}}
                                                @if (isset($addon['price']) && $addon['price'] != 0 && $addon['price'] != '' )

                                                    <small>(@price({{number_format($addon['price'])}}))</small>
                                                @else
                                                   <small>({{__('FREE')}})</small>
                                                @endif
                                            </span>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </td>
                    </tr>

                    @endforeach
                </table>

            </div>
        </div>

    </div>

    @endforeach

    <div class="card-footer">
        <h4 class="float-right text-info price">
            <small>{{__('Courses & Add-ons')}}</small><br />
            {{__('Sub-total')}}: @price({{number_format($booking->invoice['courses'] + $booking->invoice['addons'])}})
        </h4>
        <div class="clearfix"></div>
    </div>
    <hr>
</div>

@if (isset($booking->invoice['details']['accomodations']) && !empty($booking->invoice['details']['accommodations']))
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
                        @foreach ( $booking->invoice['details']['accommodations'] as $accommodation)
                        <tr>
                            <td>
                                <h6 class="mb-1 text-dark"> {{$accommodation['option']}}</h6>
                            </td>
                            <td>
                                <strong
                                    class="text-info price">@price({{number_format($accommodation['price'])}})</strong>
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
                                    {{__('Sub-total')}}: @price({{$booking->invoice['accommodation']}})</h4>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>
</div>
@endif


@if (isset($booking->invoice['details']['transfer']) && !empty($booking->invoice['details']['transfer']))

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
                        @foreach ( $booking->invoice['details']['transfer'] as $transfer)
                        <tr>
                            <td>
                                <h6 class="mb-1 text-dark">{{$transfer['option']}}</h6>
                            </td>
                            <td>
                                <strong class="text-info price">@price({{$transfer['price']}})</strong>
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

                                    {{__('Sub-total')}}: @price({{$booking->invoice['transfer']}})</h4>

                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endif

@if(
    isset($booking->invoice['details']['price']['total_before_discount']) &&
    $booking->invoice['details']['price']['total_before_discount'] > 0 &&
    $booking->promocodeables->first()->isActive()
)
    <div class="card">
        <div class="card-body p-20">
            <h3 class="card-title">{{__('Discount')}}</h3>
            <div class="bg-light-grey">
                <div class="list-group d-block w-100">
                    <table class="table table-striped">
                        <thead>
                        <th>
                            <h5>{{__('Code')}}</h5>
                        </th>
                        <th>
                            <h5>{{__('Price')}}</h5>
                        </th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <h6 class="mb-1 text-dark">{{$booking->promocodeables->first()->code}}</h6>
                                </td>
                                <td>
                                    <strong class="text-info price">
                                        {{$booking->promocodeables->first()->reward}} {{ $booking->promocodeables->first()->type() }}
                                    </strong>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endif

<div class="alert bg-default row">
    <div class="col-md-12 clearfix">
        <h3 class="float-right text-success m-b-0 p-b-0">
            <small>{{__('YOUR PRICE:')}}</small>
            @if(
                isset($booking->invoice['details']['price']['total_before_discount']) &&
                $booking->invoice['details']['price']['total_before_discount'] > 0 &&
                $booking->promocodeables->first()->isActive()
            )

                <del>@price({{ number_format($booking->invoice['details']['price']['total_before_discount'] , 2)}})<small>{{__('/child')}}</small></del> -
                @price({{number_format($booking->invoice['totalPrice'] , 2)}})<small>{{__('/child')}}</small>
            @else
                @price({{ number_format($booking->invoice['totalPrice'] , 2)}})<small>{{__('/child')}}</small>
            @endif
        </h3>
    </div>
</div>
