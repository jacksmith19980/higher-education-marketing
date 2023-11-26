<div>
    @if (isset($payment->properties['product']))

    @foreach ($payment->properties['product'] as $product)

    @include('back.applications.payments._partials.product' ,
    [
    'product' => $product,
    'order' => $loop->index
    ]
    )

    @endforeach

    @else

    @include('back.applications.payments._partials.product' ,
    [
    'product' => null,
    'order' => 0
    ]
    )

    @endif



</div>
<div class="repeated_fields_wrapper"></div>
