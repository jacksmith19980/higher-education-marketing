@component('mail::message')
@if ($content)
{!! $content !!}
@else
Dear {{$data['first_name']}},
Thank you for your interest
{!! $settings['school']['email_signature'] !!}
@endif
@endcomponent
