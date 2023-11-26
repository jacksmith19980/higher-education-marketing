@extends('back.layouts.default')

@section('styles')
<style>
.nav li a {
    display: block;
    background: #f6f6f6;
    text-decoration: none;
    color: #151515;
    padding: 10px 30px 10px 30px;
    box-shadow: rgb(15 15 15 / 5%) 0px 0px 10px 1px;
}
.nav li a.active {
    background: #ffffff!important;
    border-bottom: none!important;
    color: #2d5c7a;  
}
li:has(> a.active) {
    border-right:  1px solid #f6f6f6;
    border-left:   1px solid #f6f6f6;
    box-shadow: rgb(15 15 15 / 15%) 0px 0px 10px 1px;
}
@-moz-document url-prefix() {
ul#pills-tab li.nav-item a.active {
    border-right:  1px solid #f6f6f6;
    border-left:   1px solid #f6f6f6;
    box-shadow: rgb(15 15 15 / 15%) 0px 0px 10px 1px;
  }
}
.custom-search-bar {
        padding-left: 40px;
    }
@media (max-width: 1000px) {
    .custom-search-bar {
        padding-left: 60px;
    }
}
@media (max-width: 770px) {
    .custom-search-bar {
        padding-left: 0;
    }
}
</style>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="container-fluid">

        <div class="col-12">
            <div class="card">
                <div class="card-body" id="table-header">
                    <div class="row align-items-center">
                        <div class="col order-1 float-left">
                            <h3 class="page-title">{{$agency->name}}</h3>
                        </div>
                        <div class="col order-2">
                            <a href="javascript:void(0)" class="link float-right mr-3" data-toggle="tooltip" data-placement="top" title="Agents">
                                <h4> <i class="mdi mdi-account-multiple"></i> {{$agency->agents()->count()}}</h4>
                            </a>
                            <a href="javascript:void(0)" class="link float-right mr-3" data-toggle="tooltip" data-placement="top" title="Students">
                                 <h4> <i class="mdi mdi-school"></i> {{$agency->students()->count()}}</h4>
                            </a>
                        </div>
                        <div class="col order-3" style="white-space: nowrap;">
                            @if ($agencyEmail = $agency->email)
                                <p class="mb-0 text-right">Email address: {{$agencyEmail}}</p>
                            @endif
                            @if ($agencyPhone = $agency->phone)
                                <p class="mb-0 text-right">Phone: {{$agencyPhone}}</p>
                            @endif
                            @if ( $agencyAddress = $agency->address)
                                <p class="mb-0 text-right">Address: {{$agencyAddress}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <ul class="nav nav-pills custom-pills" id="pills-tab" style="margin-bottom: -5px!important;" role="tablist">
                <li class="nav-item"> 
                    <a class="nav-link active" data-toggle="pill" href="#students" role="tab" aria-controls="pills-timeline" aria-selected="true">{{__('Applicants')}}</a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link" data-toggle="pill" href="#agents" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Agents')}}</a>
                </li>
            </ul>
        </div>
        <div class="col-12">
            <div class="card" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px;">
                
                <div class="tab-content tabcontent-border">
                    <div class="tab-pane active p-20" style="padding-top: 35px;" id="students" role="tabpanel">
                        @include('back.agencies._partials.new-agency-students')
                    </div>
                    <div class="tab-pane p-20" id="agents" role="tabpanel">
                        @include('back.agencies._partials.new-agency-agents')
                    <div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection