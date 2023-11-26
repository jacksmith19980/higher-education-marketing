@if(!empty($cart['programs']))
    @include('front.applications.application-layouts.oiart.cartReview.partials.program-price-table', [
        'label' => 'Programs',
        'field' => $field,
        'program' => $cart['programs']
    ])
@endif

@if(!empty($cart['courses']))
    @include('front.applications.application-layouts.oiart.cartReview.partials.course-price-table', [
        'label' => 'Courses',
        'field' => $field,
        'courses' => $cart['courses']
    ])
@endif

@if(!empty($cart['addons']))
    @include('front.applications.application-layouts.oiart.cartReview.partials.addons-price-table', [
        'label' => 'Addons',
        'field' => $field,
        'addons' => $cart['addons']
    ])
@endif


@if($field->properties['price'])
    @php
    $total = \App\Helpers\cart\CartHelpers::getCartTotalPrice($cart)
    @endphp
    <div class="alert bg-default row">
        <div class="col-md-12 clearfix">
            <h3 class="float-right text-success m-b-0 p-b-0">
                <small>{!! __('YOUR PRICE') !!}:</small>
                @price({{ money_format('%.2n', $total['total'])}})
            </h3>
        </div>
    </div>
@endif

@include('front.applications.application-layouts.shared.partials.invoice')
