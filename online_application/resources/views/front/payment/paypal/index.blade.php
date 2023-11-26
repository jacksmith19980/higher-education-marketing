@extends('front.layouts.payment')

@section('content')

<div class="page-wrapper" style="padding-top:30px;display:block">

    <div class="container-fluid">

        <div class="row">

            @if (!isset($properties))

                @php

                    $properties = $paymentGateWay->properties;

                @endphp

            @endif

                <div class="col-md-6 offset-md-3 mt-5">

                    <div class="card">

                        <div class="card-body">



                            @isset($properties['before_payment_text'])

                                <div class="clearfix m-b-20">

                                    {!! __($properties['before_payment_text'])  !!}

                                </div>

                            @endisset



                        <div class="d-flex justify-content-center">

                            <script src="https://www.paypalobjects.com/api/checkout.js"></script>



                            <div id="paypal-button"



                                data-amount="{{$invoice->total}}"

                                data-invoice-number = "{{$invoice->uid}}"

                                data-env = "{{ (isset($properties['is_sandbox_account'])) ? 'sandbox' : 'production' }}"

                                data-currency = 'CAD'

                                data-client-id = "{{$properties['client_id']}}"

                                data-thank-you="{{ isset($properties['payment_thank_you']) ? $properties['payment_thank_you'] :   route('application.index' , ['school' => $school] ) }}"



                                data-callback-url={{ route('payment.track' , ['school' => $school , 'student' => auth()->guard('student')->user()] ) }}

                                >

                                </div>

                            </div>

                        </div>

                    </div>





                </div>

        </div>

    </div>

</div>

@endsection
