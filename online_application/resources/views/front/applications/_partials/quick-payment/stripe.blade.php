  <script src="https://checkout.stripe.com/checkout.js"></script>

  <div id="stripPaymentButton" class="mt-5 strip-payment-btn"
      data-school-name="{{$school->name}}"
      data-api-key="{{$paymentGateway->properties['api_key']}}"
      data-logo ="{{ isset($settings['branding']['logo']) ? Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))  : 'https://stripe.com/img/documentation/checkout/marketplace.png' }}"
      data-description="{{$application->title}}"
      data-currency="{{isset($settings['school']['default_currency']) ? QuotationHelpers::getCurrencyString($settings['school']['default_currency']) : 'usd' }}"
      data-email=""
      data-amount = "{{ $booking->invoice['totalPrice'] * 100 }}"
      data-call-back="{{route('application.payment.pay.no-login' , [
                                  'school'        => $school,
                                  'gateway'       => 'stripe',
                                  'ref'           => $invoice->uid,
                                  'application'   => $application,
                                  'invoice'       => $invoice
                              ])}}"
      data-response-call-back = "{{route('payment.response' , [
                                  'school'        => $school,
                                  'gateway'       => 'stripe',
                              ])}}"
    >
      <span class="d-block">{{__('Pay Now')}}</span>
      <small>
        {{__('Amount:')}} {{$settings['school']['default_currency']}}{{$booking->invoice['totalPrice']}}
      </small>
  </div>
