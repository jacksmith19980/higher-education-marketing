@php
    $plugin = \App\Tenant\Models\Plugin::findOrFail($field->properties['plugin']);
@endphp
@include('front.applications.application-layouts.iframe.paymentForm.' . $plugin->name . '-form')