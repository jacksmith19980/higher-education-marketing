@extends('front.layouts.minimal')
@section('content')

<div class="page-wrapper" style="padding-top: 100px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header" style="background-color: #fff;">
                        <h4 class="">{{__('Submitted Applications')}}</h4>
                    </div>
                </div>
                <div class="sec-app-dashboard">
                    <div class="app-dashboard-container p-4 mb-4 box-shadow ">

                        <div class="app-dashboard-content">
                            <div class="d-none d-md-block d-md-flex-header">
                                <div class="d-flex">
                                    <div class="content-app col-md-6 p-3 col-lg-7 pl-4">
                                        <h3 class="app-col-title text-primary">Applications</h3>
                                    </div>
                                    <div class="status-app col-md-3 p-3 col-lg-2">
                                        <h3 class="app-col-title text-primary">Status</h3>
                                    </div>
                                    <div class="reg-app col-md-3 col-lg-3 p-3">
                                        <h3 class="app-col-title text-primary">Registration Fee</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex-content">
                                @if($bookings->count())

                                     @include('front.applications._partials.booking.bookings')

                                @else

                                    @if ($userApplication->count())
                                        @include('front.applications._partials.application.applications' , ['userApplication' => $userApplication])
                                    @endif

                                @endif
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@include('front.applications._partials.application.notifications')
@endsection
