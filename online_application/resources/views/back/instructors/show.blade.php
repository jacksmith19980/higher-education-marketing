@extends('back.layouts.core.helpers.table' , 
        [
            'show_buttons' => false,
            'title'        => $instructor->name,
        ]
    )
@section('table-content')

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <center class="m-t-30">
                            <img src="{{$instructor->avatar}}" class="rounded-circle" width="150"/>
                            <h4 class="card-title m-t-10">{{$instructor->name}}</h4>
                            <h6 class="card-subtitle">{{$instructor->email}}</h6>
                        </center>
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
                            <a class="nav-link active" id="pills-lessons-tab" data-toggle="pill" href="#lessons" role="tab"
                               aria-controls="pills-lessons" aria-selected="false">{{__('Lessons')}}</a>
                        </li>

                    </ul>

                    <div class="tab-content" id="pills-tabContent">

                        @include('back.instructors._partials.lessons')

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection