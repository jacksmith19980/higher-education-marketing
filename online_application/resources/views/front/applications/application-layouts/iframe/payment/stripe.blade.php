@if (!isset($properties['api_key']))

  @php
    $properties = $field->payment->properties;
  @endphp

@endif
<script src="https://js.stripe.com/v3/"></script>
<div class="page-wrapper" style="width:100%;padding-top:100px;display:block;">
    <div class="col-md-10 offset-md-1 justify-content-center">
        <div class="card">

          <div class='hidden card-body alert alert-warning proccessing-box'>Processing......</div>

          <div class="card-body" id="paymentContainer">
              @isset($properties['before_payment_text'])
                  <div class="clearfix m-b-20">
                    {!! __($properties['before_payment_text']) !!}
                  </div>
                @endisset

                <script src="https://checkout.stripe.com/checkout.js"></script>
                  <center>
                  <div id="stripPaymentButton"
                    class="strip-payment-btn"
                    data-school-name="{{$school->name}}" data-api-key="{{$properties['api_key']}}"
                    data-logo="{{ isset($settings['branding']['logo']) ? Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))  : 'https://stripe.com/img/documentation/checkout/marketplace.png' }}"
                    data-description=""
                    data-currency="{{isset($settings['school']['default_currency']) ? QuotationHelpers::getCurrencyString($settings['school']['default_currency']) : 'usd' }}"
                    data-email="" data-amount="{{ ($application->properties['application_fees']) * 100 }}" data-call-back="{{route('application.payment.pay.no-login' , [
                                  'school'        => $school,
                                  'gateway'       => 'stripe',
                                  'ref'           => $ref = Str::random(5),
                                  'application'   => $application,
                                  'invoice'       => $ref
                              ])}}"
                    data-response-call-back="{{route('payment.response' , [
                                  'school'        => $school,
                                  'gateway'       => 'stripe',
                              ])}}">
                    <span class="d-block">{{__('Pay Now')}}</span>
                  </center>
                </div>
          </div>
    </div>
</div>
