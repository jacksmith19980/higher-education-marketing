@extends('front.layouts.agents')

@section('content')
    <div class="page-wrapper" style="padding-top: 100px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sec-app-dashboard no-pseudo-border">
                        <div class="app-dashboard-container p-4 mb-4 box-shadow ">

            <div class="app-dashboard-header">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-7">
                        <div class="d-flex align-items-center">
                            <div class="col flex-grow-0 p-0">
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <form id="imageUploadForm" name="imageUploadForm" method="POST" action="{{ route('school.profileimage.upload' , $school)}}" enctype="multipart/form-data" style="display: none;">
                                            <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" onchange="app.loadPreview(this);" data-route="" name="file">
                                            <label for="imageUpload"><i class="fas fa-camera"></i></label>
                                            <input type="hidden" name="stId" value="{{$applicant->id}}">
                                        </form>
                                    </div>
                                    <div class="avatar-preview" id="{{ $applicant->avatar }}">
                                        @if($applicant->avatar)
                                            @php
                                                $storageUrl = env('AWS_URL').$applicant->avatar;

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
                                    <h4 class="mb-1">{{$applicant->name}}</h4>
                                    <p class="mb-1">{{ $applicant->email }}</p>
                                    <small class="d-inline-block font-weight-bold">{{ucfirst($applicant->stage)}}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <p class="applicant-adrs mb-3 md-sm-4 py-2 py-md-0">
                            <span class="applicant-adrs-street">{{$applicant->address}}</span>
                            <span class="applicant-adrs-city">{{$applicant->city}}</span> <span class="applicant-adrs-country">{{$applicant->country}}</span>
                            <span class="applicant-adrs-zip">{{$applicant->postal_code}}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="student-record-content">
                <nav class="mb-3">
                    <div class="nav nav-tabs inline-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active text-uppercase" id="nav-home-tab" data-toggle="tab" href="#nav-application"
                           role="tab" aria-controls="nav-application" aria-selected="true">{{__('Application')}}</a>
                        <a class="nav-link text-uppercase" id="nav-profile-tab" data-toggle="tab" href="#nav-school" role="tab"
                           aria-controls="nav-school" aria-selected="false">{{__('School')}}</a>
                       {{--
                        <a class="nav-link text-uppercase" id="nav-contact-tab" data-toggle="tab" href="#nav-attendance" role="tab"
                           aria-controls="nav-attendance" aria-selected="false">{{__('Attendances')}}</a>
                        <a class="nav-link text-uppercase" id="nav-gades-tab" data-toggle="tab" href="#nav-grades" role="tab"
                           aria-controls="nav-grades text-uppercase" aria-selected="false">{{__{'Grades'}}}</a>
                             --}}
                        <a class="nav-link text-uppercase" id="nav-files-tab" data-toggle="tab" href="#nav-files" role="tab"
                           aria-controls="nav-files text-uppercase" aria-selected="false">{{__('Files')}}</a>
                        <a class="nav-link text-uppercase" id="nav-invoices-tab" data-toggle="tab" href="#nav-invoices" role="tab"
                           aria-controls="nav-invoices" aria-selected="false">{{__('Finance')}}</a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    @include('front.agent.applicants.student-applications')

                    @include('back.students._partials.student-school')

                    @include('front.agent.applicants.student-files')


                    @include('front.agent.applicants.financial')

                    {{--  @include('back.students._partials.student-attendances')

                    @include('back.students._partials.student-grades')  --}}
                </div>
            </div> <!-- end student record content -->
                        </div>
                    </div>
                </div>
            </div>
@endsection
