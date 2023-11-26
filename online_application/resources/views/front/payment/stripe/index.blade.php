@extends('front.layouts.payment')

@section('content')
<script src="https://js.stripe.com/v3/"></script>


<div class="page-wrapper" style="padding-top:100px;display:block;">
    <div class="container-fluid">
        <div class="row">

        @if (!isset($properties['api_key']))

            @php
                $properties = $paymentGateWay->properties;
            @endphp
            @endif
            <div class="col-md-10 offset-md-1 justify-content-center">
              <div class="card">

                <div class='card-body alert alert-warning proccessing-box hidden'>Processing......</div>

                  <div class="card-body" id="paymentContainer">
                    @if ($invoice->status->last()->status !='Paid')

                    @isset($properties['before_payment_text'])
                        <div class="clearfix m-b-20">
                            {!! __($properties['before_payment_text'])  !!}
                        </div>
                    @endisset
                  <script src="https://checkout.stripe.com/checkout.js"></script>

                    <div id="stripPaymentButton" class="btn waves-effect waves-light btn-rounded btn-outline-success text-uppercase"
                        data-school-name="{{$school->name}}"
                        data-api-key="{{$properties['api_key']}}"
                        data-logo ="{{ isset($settings['branding']['logo']) ? Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))  : 'https://stripe.com/img/documentation/checkout/marketplace.png' }}"
                        data-description="{{$invoice->application->title}}"
                        data-currency="{{isset($settings['school']['default_currency']) ? QuotationHelpers::getCurrencyString($settings['school']['default_currency']) : 'usd' }}"
                        data-email=""
                        data-amount = "{{ ($invoice->total) * 100 }}"
                        data-call-back="{{route('application.payment.pay.no-login' , [
                            'school'        => $school,
                            'gateway'       => 'stripe',
                            'ref'           => $invoice->uid,
                            'application'   => $paymentGateWay->application,
                            'invoice'       => $invoice
                        ])}}"
                        data-response-call-back = "{{route('payment.response' , [
                            'school'        => $school,
                            'gateway'       => 'stripe',
                        ])}}"
                    >
                        <span class="d-block">{{__('Pay Now')}}</span>
                    </div>

                    @else

                        <div class="col-md-6 offset-md-3">

                            <div class="alert alert-success text-center">

                               {{__('Thank you for your payment')}}

                            </div>

                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
