@component('mail::message')
# {{$data['subject']}}

{!! $data['body'] !!}
@if ( isset($data['include_payment_link']) )

Please, Click on the link below to pay now
@component('mail::button', ['url' => route('invoice.pay' , ['invoice' => $invoice , 'school' => $school])])
Pay Now
@endcomponent
@endif

Thanks,<br>
{{ $school->name }}
@endcomponent