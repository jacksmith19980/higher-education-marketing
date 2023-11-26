@if (!isset($properties))
  @php
    $properties = $field->payment->properties;
  @endphp
@endif

<div id="paymentContainer" class="container mt-5">
	<div class="row">

		@if (isset($properties['before_payment_text']))

			<div class="col-md-10 offset-md-1">

					{!! __($properties['before_payment_text']) !!}

			</div>

		@endif

			@if ($invoice->status->last()->status !='Paid')
					<div class="clear"></div>
					<div class="mt-5 col-md-12">
							<div class="d-flex justify-content-center" style="margin-top: -50px;">

								<script src="https://www.paypal.com/sdk/js?client-id={{$properties['client_id']}}&components=buttons&currency={{isset($properties['currency']) ? $properties['currency'] : 'CAD' }}"></script>

									<div id="paypal-button"
										style="width: 400px"
										data-amount="{{$invoice->total}}"
										data-invoice-number = "{{$invoice->uid}}"
										data-env = "{{ (isset($properties['is_sandbox_account'])) ? 'sandbox' : 'production' }}"
																														data-currency = '{{isset($properties['currency']) ? $properties['currency'] : 'CAD' }}'
										data-client-id = "{{$properties['client_id']}}"
										data-thank-you="{{ isset($properties['payment_thank_you']) ? $properties['payment_thank_you'] :   route('application.index' , ['school' => $school] ) }}"

										data-callback-url={{ route('payment.track' , ['school' => $school , 'student' => auth()->guard('student')->user()] ) }}
										>
									</div>
							</div>
					</div>
			@endif
	</div>
</div>
