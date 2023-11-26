@if ($invoice->status->last()->status !='Paid')
        <div class="clear"></div>
        <div class="mt-5 col-md-12">
                <div class="d-flex justify-content-center" style="margin-top: -50px;">
                    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
                        <div id="paypal-button"
                            data-amount="{{$invoice->total}}"
                            data-invoice-number = "{{$invoice->uid}}"
                            data-env = "{{ (isset($paymentGateway->properties['is_sandbox_account'])) ? 'sandbox' : 'production' }}"
                            data-currency = '{{isset($paymentGateway->properties['currency']) ? $paymentGateway->properties['currency'] : 'CAD' }}'
                            data-client-id = "{{$paymentGateway->properties['client_id']}}"
                            data-thank-you="{{ isset($paymentGateway->properties['payment_thank_you']) ? $paymentGateway->properties['payment_thank_you'] :   route('application.index' , ['school' => $school] ) }}"

                            data-callback-url={{ route('payment.track' , ['school' => $school , 'student' => auth()->guard('student')->user()] ) }}
                            >
                        </div>
                </div>
        </div>
@else
    Thank you for your payment
@endif
