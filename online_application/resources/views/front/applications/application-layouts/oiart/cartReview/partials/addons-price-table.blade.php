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
                @foreach($addons as $addon)
                    <tr>
                        <td>
                            <h5 class="mb-1 text-dark">
                                {{$addon['title']}}
                            </h5>
                        </td>
                        @if($field->properties['price'])
                            <td>
                                <strong class="text-info price">{{$settings['school']['default_currency']}}{{number_format($addon['price'])}}</strong>
                            </td>
                        @endif
                    </tr>
                @endforeach

                </tbody>
                @if($field->properties['price'])
                    @php
                        $price_cart = \App\Helpers\cart\CartHelpers::getCartTotalPrice($cart)
                    @endphp
                    <tfoot>
                    <tr>
                        <td style="padding-bottom: 0px;padding-top: 0px;">&nbsp;</td>
                        <td style="padding-bottom: 0px;padding-top: 0px;">
                            <h4 class="float-right text-info price">
                                {{__('Sub Total')}}: {{$settings['school']['default_currency']}}{{$price_cart['addons']}}</h4>
                        </td>
                    </tr>

                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>