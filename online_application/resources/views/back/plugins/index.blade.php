@extends('back.layouts.default')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="float-left">
                    <h4 class="page-title">{{__('Plugins & Integrations')}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="title">{{__('Communications')}}</h4>
<div class="row">
    <div class="col-3">
        @include('back.plugins.twilio.twilio' , [
        'helper' => $pluginsHelper->plugin('twilio')
        ])
    </div>
</div>
<h4 class="title">{{__('E-Signature')}}</h4>
<div class="row">
    <div class="col-3">
        @include('back.plugins.adobesign.adobesign', [
        'helper' => $pluginsHelper->plugin('adobesign')
        ])
    </div>
    <div class="col-3">
        @include('back.plugins.docusign.docusign', [
        'helper' => $pluginsHelper->plugin('docusign')
        ])
    </div>

    <div class="col-3">
        @include('back.plugins.pandadoc.pandadoc' , [
        'helper' => $pluginsHelper->plugin('pandadoc'),
        'config' => $pluginsHelper->plugin('pandadoc')->config()
        ])
    </div>
    <div class="col-3">
        @include('back.plugins.eversign.eversign' , [
        'helper' => $pluginsHelper->plugin('eversign')
        ])
    </div>
</div>
<h4>{{__('Payment')}}</h4>
<div class="row">
    <div class="col-3">
        @include('back.plugins.stripe.stripe', [
        'helper' => $pluginsHelper->plugin('stripe')
        ])
    </div>
</div>
<h4>{{__('3rd Party Integrations')}}</h4>
<div class="row">
    <div class="col-3">
        @include('back.plugins.mautic.mautic', [
        'helper' => $pluginsHelper->plugin('mautic')
        ])
    </div>
    <div class="col-3">
        @include('back.plugins.hubspot.hubspot', [
        'helper' => $pluginsHelper->plugin('hubspot')
        ])
    </div>
    <div class="col-3">
        @include('back.plugins.campuslogin.campuslogin', [
        'helper' => $pluginsHelper->plugin('campuslogin')
        ])
    </div>
    <div class="col-3">
        @include('back.plugins.webhook.webhook', [
        'helper' => $pluginsHelper->plugin('webhook')
        ])
    </div>
</div>
{{-- <h4>{{__('Webhook')}}</h4>
<div class="row">
    <div class="col-3">
        @include('back.plugins.webhook.webhook', [
        'helper' => $pluginsHelper->plugin('webhook')
        ])
    </div>
</div> --}}
@endsection
