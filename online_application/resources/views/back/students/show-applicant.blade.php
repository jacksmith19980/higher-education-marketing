@extends('back.layouts.default')
{{-- @section('table-content') --}}
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="sec-app-dashboard no-pseudo-border">
                    <div class="p-4 mb-4 app-dashboard-container box-shadow ">
                        <div class="app-dashboard-header">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-lg-7">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-0 p-0 col">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <form id="imageUploadForm" name="imageUploadForm" method="POST"
                                                            action="{{ route('school.profileimage.upload' , $school)}}"
                                                            enctype="multipart/form-data" style="display: none;">
                                                        <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg"
                                                                onchange="app.loadPreview(this);" data-route=""
                                                                name="file">
                                                        <label for="imageUpload"><i class="fas fa-camera"></i></label>
                                                        <input type="hidden" name="stId" value="{{$applicant->id}}">
                                                    </form>
                                                </div>
                                                <div class="avatar-preview" id="{{ $applicant->avatar }}">
                                                    @if($applicant->avatar)
                                                        @php
                                                            $storageUrl = env('AWS_URL').$applicant->avatar;

                                                        @endphp
                                                        <img src="{{$storageUrl}}" id="imagePreview"
                                                                style="width: 100%; height: 100%; border-radius:100%;">
                                                    @else
                                                        <img src="{{ asset('media/images/blankavatar.png') }}"
                                                                id="imagePreview"
                                                                style="width: 100%; height: 100%; border-radius:100%;">
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
                                                @if(!is_array($applicant->phone) && $applicant->phone != '')
                                                    <p class="mb-1">{{ $applicant->phone }}</p>
                                                @elseif(is_array($applicant->phone))
                                                    <p class="mb-1">{{ $applicant->phone['phone'] }}</p>
                                                @endif
                                                @php
                                                    $disabled = !PermissionHelpers::checkActionPermission('contact', 'edit', $applicant) ? 'data-disabled="disabled"' : ''
                                                @endphp
                                                <div class="d-flex">
                                                    <div>
                                                        <i class="fas fa-tag mr-1 text-muted"></i>
                                                        <span
                                                            {{$disabled}}
                                                            class="editable editable-click editable-open singleClick"
                                                            data-placement="top"
                                                            data-name="contact-type"
                                                            data-type="select"
                                                            data-source="{{ route('student.quick-edit.source', [
                                                                'student' => $applicant,
                                                                'source' => 'contact-type',
                                                            ]) }}"
                                                            data-value="{{$applicant->stage}}"
                                                            data-url="{{
                                                                route('student.quick-edit.update', [
                                                                'student' => $applicant,
                                                                'source' => 'contact-type',
                                                                ])}}"
                                                            data-validation="{&quot;required&quot;:&quot;This field is required&quot;}"
                                                            data-original-title="
                                                            {{__('Contact Type')}}"
                                                        >
                                                            {{ucfirst($applicant->stage)}}
                                                        </span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <i class="fas fa-building mr-1 text-muted"></i>
                                                        <span
                                                            {{$disabled}}
                                                            class="editable editable-click editable-open singleClick"
                                                            data-placement="top"
                                                            data-name="campus"
                                                            data-type="select"
                                                            data-source="{{ route('student.quick-edit.source', [
                                                                'student' => $applicant,
                                                                'source' => 'campus',
                                                            ]) }}"
                                                            data-value="{{($applicant->campus) ? $applicant->campus->id : ''}}"
                                                            data-url="{{
                                                                route('student.quick-edit.update', [
                                                                'student' => $applicant,
                                                                'source' => 'campus',
                                                                ])}}"
                                                            data-validation="{&quot;required&quot;:&quot;This field is required&quot;}"
                                                            data-emptytext='{{__('No Campus')}}'
                                                            data-original-title="{{__('Campus')}}"
                                                        >
                                                            {{($applicant->campus) ? $applicant->campus->title : null}}
                                                        </span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <i class="fas fa-user mr-1 text-muted"></i>
                                                        <span
                                                            {{$disabled}}
                                                            class="editable editable-click editable-open singleClick"
                                                            data-placement="top"
                                                            data-name="owner"
                                                            data-type="select"
                                                            data-source="{{ route('student.quick-edit.source', [
                                                                'student' => $applicant,
                                                                'source' => 'owner',
                                                            ]) }}"
                                                            data-value="{{($applicant->owner) ? $applicant->owner->id : ''}}"
                                                            data-url="{{
                                                                route('student.quick-edit.update', [
                                                                'student' => $applicant,
                                                                'source' => 'owner',
                                                                ])}}"
                                                            data-validation="{&quot;required&quot;:&quot;This field is required&quot;}"
                                                            data-emptytext='{{__('No Owner')}}'
                                                            data-original-title="{{__('Owner')}}"
                                                        >
                                                            {{($applicant->owner) ? $applicant->owner->name : null}}
                                                        </span>
                                                    </div>
                                                    @if($applicant->agent)
                                                        <div class="ml-3">
                                                            <i class="mdi mdi-store-24-hour mr-1 text-muted"></i>
                                                            <span>
                                                                {{$applicant->agent->agency->name}}
                                                                <small>({{$applicant->agent->name}})</small>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <p class="mt-1 mb-1">
                                                    Recent transaction: <span>{{ $student->recent_transaction_description }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-5">
                                    <a href="{{route('student.impersonate' , $student)}}" class="btn btn-success float-right mt-3">{{__('Impersonate')}}</a>
                                    <p class="py-2 mb-3 applicant-adrs md-sm-4 py-md-0">
                                        <strong>{{__('Address')}}</strong>
                                        @if($applicant->address)
                                            <span class="d-block">
                                                {{$applicant->address}}
                                            </span>
                                        @endif
                                        @if($applicant->city)
                                            <span class="d-block">
                                                {{$applicant->city}}
                                            </span>
                                        @endif
                                        @if($applicant->postal_code)
                                            <span class="d-block">
                                                {{$applicant->postal_code}}
                                            </span>
                                        @endif

                                        @if($applicant->country)
                                            <span class="d-block">
                                                {{$applicant->country}}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="student-record-content">
                            <nav class="mb-3">
                                <div class="nav nav-tabs inline-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-link active text-uppercase" id="nav-home-tab" data-toggle="tab"
                                        href="#nav-profile"
                                       role="tab" aria-controls="nav-profile"
                                       aria-selected="true">{{__('Profile')}}</a>

                                        <a class="nav-link text-uppercase" id="nav-home-tab" data-toggle="tab"
                                        href="#nav-application"
                                       role="tab" aria-controls="nav-application"
                                       aria-selected="true">{{__('Application Submissions')}}</a>


                                    <a class="nav-link text-uppercase" id="nav-history-tab" data-toggle="tab"
                                        href="#nav-history" role="tab"
                                       aria-controls="nav-history"
                                       aria-selected="false">{{__('Submission History')}}</a>
                                    <a class="nav-link text-uppercase" id="nav-files-tab" data-toggle="tab"
                                       href="#nav-files" role="tab"
                                       aria-selected="false">{{__('Files')}}</a>
                                    <a class="nav-link text-uppercase" id="nav-invoices-tab" data-toggle="tab"
                                       href="#nav-invoices" role="tab"
                                       aria-controls="nav-invoices" aria-selected="false">{{__('Finance')}}</a>
                                    <a class="nav-link text-uppercase" id="nav-contracts-tab" data-toggle="tab"
                                       href="#nav-contracts" role="tab"
                                       aria-controls="nav-contracts"
                                       aria-selected="false">{{__('Contracts')}}</a>
                                    <a class="nav-link text-uppercase" id="nav-shareables-tab" data-toggle="tab"
                                       href="#nav-shareables" role="tab"
                                       aria-controls="nav-shareables"
                                       aria-selected="false">{{__('Documents')}}</a>
                                    <a class="nav-link text-uppercase" id="nav-messages-tab" data-toggle="tab"
                                       href="#nav-messages" role="tab"
                                       aria-controls="nav-messages"
                                       aria-selected="false">{{__('Messages')}}</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                @include('back.students._partials.student-profile')
                                @include('back.students._partials.student-applications')
                                @include('back.students._partials.student-history')
                                @include('back.students._partials.student-files')
                                @include('back.students._partials.student-invoices')
                                @include('back.students._partials.student-contracts' , ['contracts' => $student->contracts])
                                @include('back.students._partials.student-shareables' , ['shareables' =>  $student->shareables])
                                @include('back.students._partials.student-messages')

                            </div>
                        </div> <!-- end student record content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
