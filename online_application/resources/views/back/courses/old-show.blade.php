@extends('back.layouts.core.helpers.table' ,
[
'show_buttons' => false,
'title' => $course->title,
]
)
@section('table-content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-lg-3 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30">
                        <h4 class="card-title m-t-10">{{$course->title}}</h4>
                        <h6 class="card-subtitle">
                            @foreach ($courseCampuses as $campusName => $id)
                                <span class="badge badge-pill badge-info">{{$campusName}}</span>
                            @endforeach
                        </h6>
                    </center>

                    <div class="card-body">
                       
                        @if (isset($course->properties['course_registeration_fee']))
                            <small class="text-muted">{{__('Registration Fee')}}</small>
                            <h6>{{$course->properties['course_registeration_fee']}}</h6>
                        @endif

                        @if (isset($course->properties['course_materials_fee']))
                            <small class="text-muted">{{__('Materials Fee')}}</small>
                            <h6>{{$course->properties['course_materials_fee']}}</h6>
                        @endif
                    </div>

                </div>
                <div>
                    <hr>
                </div>
            </div>
        </div>


        <div class="col-lg-9 col-xlg-9 col-md-7">
            <div class="card">

                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-pricing-tab" data-toggle="pill" href="#pricing"
                            role="tab" aria-controls="pills-pricing" aria-selected="true">
                            {{__('Pricing')}}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="pills-dates-tab" data-toggle="pill" href="#dates" role="tab"
                            aria-controls="pills-dates" aria-selected="false">
                            {{__('Dates')}}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="pills-addons-tab" data-toggle="pill" href="#addons" role="tab"
                            aria-controls="pills-addons" aria-selected="false">
                            {{__('Addons')}}
                        </a>
                    </li>

                </ul>

                <div class="tab-content" id="pills-tabContent">

                    @include('back.courses._partials.pricing.course-pricing-tab')

                    @include('back.courses._partials.dates.course-dates-tab')

                    @include('back.courses._partials.addons.course-addons-tab')

                </div>
            </div>

        </div>
    </div>
</div>
@endsection