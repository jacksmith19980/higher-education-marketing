@extends('back.layouts.core.helpers.table' , 
        [
            'show_buttons' => false,
            'title'        => $agency->name,
        ]
    )
@section('table-content')

<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title m-t-10">{{$agency->name}}</h4>
                    <h6 class="card-subtitle">{{$agency->description}}</h6>
                    <div class="row text-center justify-content-md-center">
                        
                        
                        <div class="col-4 m-t-10">
                            <a href="javascript:void(0)" class="link" data-toggle="tooltip" data-placement="top" title="Students">
                                <i class="mdi mdi-school"></i> <font class="font-medium">{{$agency->students()->count()}}</font>
                            </a>
                        </div>


                        <div class="col-4 m-t-10">
                            <a href="javascript:void(0)" class="link" data-toggle="tooltip" data-placement="top" title="Agents">
                            <i class="mdi mdi-account-multiple"></i> <font class="font-medium">{{$agency->agents()->count()}}</font></a>
                        </div>

                    </div>
                    <hr> 
                  
                    @if ($agencyEmail = $agency->email)
                        <small class="text-muted">Email address </small>
                        <h6>{{$agencyEmail}}</h6>
                    @endif
                    
                    
                    @if ($agencyPhone = $agency->phone)
                    <small class="text-muted p-t-30 db">Phone</small>
                    <h6>{{$agencyPhone}}</h6>
                    @endif
                    
                    @if ( $agencyAddress = $agency->address)
                    @php
                        $fulladdress = $agencyAddress.', '.$agency->postal_code .' '.$agency->city ;
                    @endphp

                    <small class="text-muted p-t-30 db">Address</small>
                        <h6>{{$fulladdress}}</h6>
                        <h6>{!! ($flag) ? $flag : '' !!} {{$agency->country}}</h6>
                        
                        @if ($map)
                            <div class="map-box m-t-15">
                                <div class="mapouter">
                                    <div class="gmap_canvas">
                                        {!! $map !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    
                </div>
            </div>
        </div>






        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">

                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-timeline-tab" data-toggle="pill" href="#students" role="tab" aria-controls="pills-timeline" aria-selected="true">{{__('Students')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#agents" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Agents')}}</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    
                    @include('back.agencies._partials.agency-students')

                    @include('back.agencies._partials.agency-agents')
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection