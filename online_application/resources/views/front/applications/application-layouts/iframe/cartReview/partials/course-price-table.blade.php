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

                @foreach($courses as $course)
                    <tr>
                        <td>
                            <h5 class="mb-1 text-dark">
                                {{$course['title']}}
                            </h5>
                            <h6 class="mb-1 text-dark">
                                {{$course['date']['start']}}
                            </h6>
                        </td>
                        @if($field->properties['price'])
                            <td>
                                <strong class="text-info price">{{$settings['school']['default_currency']}}{{number_format($course['price'])}}</strong>
                            </td>
                        @endif
                    </tr>
                @endforeach

                @foreach($courses as $course)
                    @if(array_key_exists('addons', $course['date']))
                        @foreach($course['date']['addons'] as $addons)
                            <tr>
                                <td>
                                    <h5 class="mb-1 text-dark">
                                        {{$addons['key']}}
                                    </h5>
                                </td>
                                @if($field->properties['price'])
                                    <td>
                                        <strong class="text-info price">{{$settings['school']['default_currency']}}{{number_format($addons['price'])}}</strong>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                @endforeach

                @if(array_key_exists('addons', $course))
                    @foreach($course['addons'] as $addons)
                        @php
                        $addon_obj = \App\Tenant\Models\Addon::where('key', $addons['key'])->first();
                        @endphp
                        <tr>
                            <td>
                                <h5 class="mb-1 text-dark">
                                    {{$addon_obj->title}}
                                </h5>
                            </td>
                            @if($field->properties['price'])
                                <td>
                                    <strong class="text-info price">{{$settings['school']['default_currency']}}{{number_format($addons['price'])}}</strong>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif

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
                                {{__('Sub Total')}}
                                : {{$settings['school']['default_currency']}}{{$price_cart['courses']}}</h4>
                        </td>
                    </tr>

                    </tfoot>
                @endif
            </table>

        </div>
    </div>
</div>