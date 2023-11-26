@if (!isset($properties))
    @php
        $properties = $field->payment->properties;
    @endphp
@endif

@php
    $invoiceStatus = $invoice->status->last();
@endphp
<div class="container mt-5">
    <div class="row">


        @if (isset($properties['before_payment_text']))

        <div class="col-md-10 offset-md-1">

            {!! __($properties['before_payment_text']) !!}

        </div>

        @endif

        @if ($invoiceStatus->status !='Paid')
        <div class="clear"></div>
        <div class="col-md-12 mt-5">
            <div class="d-flex justify-content-center" style="margin-top: -50px;">

                @if(isset($properties['is_sandbox_account']) && $properties['is_sandbox_account'] == 1 )
                <script src="https://pay.sandbox.datatrans.com/upp/payment/js/datatrans-2.0.0.min.js"></script>

                @else
                    
                <script src="https://pay.datatrans.com/upp/payment/js/datatrans-2.0.0.min.js"></script>

                @endif

                
                <div id="paymentForm" 
                data-merchant-id="{{$properties['merchant_id']}}" 
                data-amount="{{ ($invoice->total) * 100 }}" 
                data-currency="CHF" 
                data-refno="{{$invoice->uid}}" 
                data-sign="{{$properties['merchant_sign']}}"
                {{-- data-success-url="{{route('payment.response' , [
                    'school'    => $school,
                    'invoice'   => $invoice
                ])}}" --}}
                
                {{--  data-post-url="{{ route('payment.track.test' , ['school' => $school , 'student' => auth()->guard('student')->user()] ) }}"  --}}
                >
                    <button id="paymentButton" class="btn btn-success">Pay Now</button>
            </div>
                
            </div>
        </div>
        @endif
    </div>
</div>

<script type="text/javascript">
    document.getElementById("paymentButton").onclick = function () {
        Datatrans.startPayment({form: '#paymentForm'});
    }; 
</script>