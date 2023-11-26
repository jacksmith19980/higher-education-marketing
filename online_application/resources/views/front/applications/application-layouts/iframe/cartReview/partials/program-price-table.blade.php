<div class="card-body" style="padding-bottom: 0px;padding-top: 0px;">
    <h3 class="card-title">{!! __($label) !!}</h3>

    <div class="bg-light-grey">
        <div class="list-group d-block w-100">
            <table class="table table-striped">
                <thead>
                <th></th>
                @if($field->properties['price'])
                    <th></th>
                @endif
                </thead>

                <tbody>
                @if($program['registration_fees'] > 0)
                    <tr>
                        <td>{!! __('Registration Fees') !!}</td>
                        @if($field->properties['price'])
                        <td>
                            <strong class="text-info price">{{$settings['school']['default_currency']}}{{number_format($program['registration_fees'])}}</strong>
                        </td>
                        @endif
                    </tr>
                @endif

                <tr>
                    <td>
                        <h5 class="mb-1 text-dark">
                            {{__($program['title'])}}
                        </h5>
                        @if($program['dates_type'] === 'specific-intakes')
                            <h6>
                                {!! __('Start Date') !!}: {{$program['start_date']}}
                            </h6>
                        @else
                            @php
                                $key = array_search($program['date_schudel'], $settings['calendar']['schedule_label']);
                                $start_time = $settings['calendar']['schedule_start_time'][$key];
                                $end_time = $settings['calendar']['schedule_end_time'][$key];
                            @endphp
                            <h6>
                                {!! __('Start Date') !!}:
                                <span style="font-weight: normal;">
                                    {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate(explode('_', $program['start_date'])[0]))}}
                                </span>
                                <br>
                                {!! __('End Date') !!}:
                                <span style="font-weight: normal;">
                                    {{iconv('latin5', 'utf-8', \App\Helpers\Date\DateHelpers::translateDate($program['end_date']))}}
                                </span>
                                <br>

                                <div class="radio-title">{{__("Start Time")}}: </div>
                                 <span style="font-weight: normal;">&nbsp; {{\App\Helpers\Quotation\QuotationHelpers::amOrPm($start_time)}}</span>
                                <br>
                                <div class="radio-title">{{__("End Time")}}: </div>
                                <span style="font-weight: normal;">&nbsp; {{\App\Helpers\Quotation\QuotationHelpers::amOrPm($end_time)}}</span>
                            </h6>
                        @endif
                    </td>
                    @if($field->properties['price'])
                        <td>
                            <strong class="text-info price">{{$settings['school']['default_currency']}}{{number_format($program['regular_price'])}}</strong>
                        </td>
                    @endif
                </tr>

                @php
                    $price_addons = 0;
                @endphp

                @foreach($program['addons'] as $addons)
                    <tr>
                        <td>
                            <h5 class="mb-1 text-dark">
                                {{$addons['addon_options']}}
                            </h5>
                            <h6>
                                Category: {{$addons['addon_options_category']}}
                            </h6>
                        </td>
                        @if($field->properties['price'])
                            @php
                              $price_addons += $addons['addon_options_price'];
                            @endphp
                            <td>
                                <strong class="text-info price">{{$settings['school']['default_currency']}}{{number_format($addons['addon_options_price'])}}</strong>
                            </td>
                        @endif
                    </tr>
                @endforeach

                </tbody>
                @if($field->properties['price'])
                    @php
                        $price_cart = \App\Helpers\cart\CartHelpers::getCartTotalPrice($cart);
                    @endphp
                    <tfoot>
                    <tr>
                        <td style="padding-bottom: 0px;padding-top: 0px;">&nbsp;</td>
                        <td style="padding-bottom: 0px;padding-top: 0px;">
                            <h4 class="float-right text-info price">
                                {!! __('Sub Total') !!}: {{$settings['school']['default_currency']}}{{$price_cart['programs']}}</h4>
                        </td>
                    </tr>

                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>