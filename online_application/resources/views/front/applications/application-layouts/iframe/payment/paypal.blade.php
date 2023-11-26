@if (!isset($properties['client_id']))
  @php
    $properties = $field->payment->properties;
  @endphp
@endif
<div class="container mt-5">
	<div class="row">
		@if (isset($properties['before_payment_text']))
			<div class="col-md-10 offset-md-1">
				{!! __($properties['before_payment_text'])  !!}
			</div>
		@endif
			<div class="clear"></div>
			<div class="col-md-12 mt-5">
					<div class="d-flex justify-content-center" style="margin-top: -50px;">
						<script src="https://www.paypalobjects.com/api/checkout.js"></script>
							<div id="paypal-button"
								data-amount="1000"
								data-invoice-number = "{{rand(5000,50000)}}"
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
