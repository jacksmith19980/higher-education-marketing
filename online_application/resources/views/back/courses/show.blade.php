@php
    $show_buttons = false;
    $title = $course->title;
@endphp
@extends('back.layouts.default')
@section('content')
<div class="row justify-content-center">
    @include('back.layouts.core.helpers.page-actions')
    <div class="col-12">
        <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#course_information" role="tab" aria-controls="pills-timeline" aria-selected="true">{{__('Course Information')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#dates_prices" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Dates/Prices')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#champs_personnalisés" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Custom Fields')}}</a>
            </li>
        </ul>
    </div>
    <div class="col-12">
        <div class="card" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px;">
            <div class="card-body" id="table-card">
                <div class="table-responsive">
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane p-20 active" id="course_information" role="tabpanel">
                            @include('back.courses._partials.course.programs')
                        </div>
                        <div class="tab-pane p-20" id="dates_prices" role="tabpanel">
                            @include('back.courses._partials.course.dates-prices')
                        </div>
                        <div class="tab-pane p-20" id="champs_personnalisés" role="tabpanel">
                            @include('back.courses._partials.course.custom-fields')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
