<a id="paymentButton"
    class="ml-1 mr-1 mt-5 d-block btn waves-effect waves-light btn-block btn-lg btn-success text-white p-3 text-uppercase"
    href="{{route('invoice.pay' , [
        'school'    => $school,
        'invoice'   => $invoice
    ])}}"
    target="_blank"
    >

    <span class="d-block">{{__('Pay Now')}}</span>

    <small>{{__('Amount:')}} @price({{number_format($booking->invoice['totalPrice'])}})</small>



</a>
