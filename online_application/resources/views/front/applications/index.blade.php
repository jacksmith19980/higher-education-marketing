@extends('front.layouts.minimal')
@section('content')
<div class="page-wrapper" style="padding-top: 100px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="sec-app-dashboard">
                    <div class="p-4 mb-4 app-dashboard-container box-shadow ">
                        <div class="app-dashboard-header">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-lg-7">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-0 p-0 col">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <form id="imageUploadForm" name="imageUploadForm" method="POST" action="{{ route('school.profileimage.upload' , $school)}}" enctype="multipart/form-data">
                                                        <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" onchange="app.loadPreview(this);" data-route="" name="file">
                                                        <label for="imageUpload"><i class="fas fa-camera"></i></label>
                                                        <input type="hidden" name="stId" value="{{$student->id}}">
                                                    </form>
                                                </div>
                                            <div class="avatar-preview" id="{{$student->avatar}}">
                                                @if($student->avatar)
                                                    @php
                                                        $storageUrl = env('AWS_URL').$student->avatar;

                                                    @endphp
                                                <img src="{{$storageUrl}}" id="imagePreview" style="width: 100%; height: 100%; border-radius:100%;">
                                                @else
                                                    <img src="{{ asset('media/images/blankavatar.png') }}" id="imagePreview" style="width: 100%; height: 100%; border-radius:100%;">
                                                @endif

                                                <!-- <div id="imagePreview" style="">
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col flex-grow-1">
                                        <div class="applicant-main-info">
                                            <p class="mb-1">{{__('Welcome back!')}}</p>
                                            <h4 class="mb-1 text-primary">{{$student->name}}</h4>
                                            <small class="d-block">{{__('Applicant')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($student->address)
                                <div class="col-md-6 col-lg-5">
                                    <strong>{{__('Address')}}</strong>
                                    <p class="py-2 mb-3 applicant-adrs md-sm-4 py-md-0">
                                        <span class="applicant-adrs-street">{{$student->address}}</span><br/>
                                        <span class="applicant-adrs-city">{{$student->city}}</span> <span class="applicant-adrs-country">{{$student->country}}</span>
                                        <span class="applicant-adrs-zip"> {{$student->postal_code}}</span>
                                    </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($bookings->count() || $student_submissions->count())
                        <div class="app-dashboard-content">
                            <div class="d-none d-md-block d-md-flex-header">
                                <div class="d-flex">
                                    <div class="p-3 pl-4 content-app col-md-6 col-lg-7">
                                        <h3 class="app-col-title text-primary">{{__('Applications')}}</h3>
                                    </div>
                                    <div class="p-3 status-app col-md-3 col-lg-2">
                                        <h3 class="app-col-title text-primary">{{__('Application Status')}}</h3>
                                    </div>
                                    @php
                                        $invoice_exist = false;
                                        foreach ($userApplication as $app) {
                                            if (count($app->invoices)) {
                                                $invoice_exist = true;
                                            }
                                        }
                                    @endphp
                                    @if($invoice_exist)
                                        <div class="p-3 reg-app col-md-3 col-lg-3">
                                            <h3 class="app-col-title text-primary">{{__('Registration Fees')}}</h3>
                                        </div>
                                    @endif
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
                        @else
                            <a class="btn btn-info waves-effect waves-light text-uppercase" href="{{ SchoolHelper::getStartNewApplicationLink() }}">
                                {{__('Start New Application')}}
                                <i class="mdi mdi-arrow-right"></i>
                            </a>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="display:none;">
        <div class="col-lg-12">
            <div class="row">
                <div class="mb-4 col-md-6">
                    <div class="card box-shadow h-100">
                        <div class="px-4 py-3 card-body">
                            <h3 class="card-title">{{__('Other Application(s)')}}</h3>
                            @foreach ($userApplication as $application)
                                @if ( !($application->status) )
                                    <div class="mb-3 d-flex justify-content-between flex-nowrap">
                                        <div class="pr-2"><i class="mr-1 fas fa-caret-right text-primary list-indicator"></i>{{__($application->title)}}</div>
                                        <div>
                                            <a class="btn btn-primary btn-sm mbtn mb-ms-2 is-uppercase"
                                                href="{{route('application.show' , ['school' => $school , 'application' => $application ])}}">
                                                {{__('start')}}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-md-6">
                </div>
            </div>
        </div>
    </div>

</div>
@include('front.applications._partials.application.notifications')


@endsection
