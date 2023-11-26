@extends('front.layouts.minimal-no-sidebar')

@section('content')
    <div class="page-wrapper payment-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sec-app-dashboard">
                        <div class="p-4 mb-2 mt-2 app-dashboard-container box-shadow ">
                            <div class="app-dashboard-header">
                                @if (isset($payment))
                                    @include('front.applications.application-layouts.'.$application->layout.'.payment.'.$payment->slug, [
                                        'properties' => $payment->properties
                                    ])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
