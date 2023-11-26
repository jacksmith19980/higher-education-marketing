@extends('front.layouts.minimal')

@section('content')
    <div class="page-wrapper" style="padding-top: 100px;">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <div class="sec-app-dashboard">
                        <div class="app-dashboard-container p-4 mb-4 box-shadow ">
                            <div class="app-dashboard-header">
                            <h2>{{$submission->application->title}}</h2>
                            </div>
                            <div class="app-dashboard-content">
                                <div class="review-page"
                                     data-route="{{route('school.submissions.review', [$school, $submission])}}">
                                    <center>
                                        <div class="lds-ellipsis">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection