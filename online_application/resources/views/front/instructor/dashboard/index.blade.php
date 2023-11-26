@extends('front.layouts.new-instructors')

@section('styles')
<style>
.fc-event{
    cursor: pointer;
}
.nav li:not(#li2) a {
    display: block;
    background: #f6f6f6;
    text-decoration: none;
    color: #151515;
    padding: 10px 30px 10px 30px;
    box-shadow: rgb(15 15 15 / 5%) 0px 0px 10px 1px;
}
.nav li:not(#li2) a.active {
    background: #ffffff!important;
    border-bottom: none!important;
    color: #2d5c7a;
}
li:not(#li2):has(> a.active) {
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
</style>
@yield('_styles')
@endsection

@section('content')

    <input id="school_name" name="school_name" type="hidden" value="{{request()->tenant()->slug}}">
    <input id="attendance_encrypted" type="hidden" value="{{Crypt::encrypt('attendance')}}">
    <input id="student_encrypted" type="hidden" value="{{Crypt::encrypt('students')}}">

    @include('front.instructor._partials.instructor-info')

    <div class="row justify-content-center">

        <div class="col-12">
            <ul class="nav nav-pills custom-pills" id="pills-tab" style="margin-bottom: -5px!important;" role="tablist">

                <li class="nav-item">

                    <a class="nav-link active" id="pills-timeline-tab" data-toggle="pill" href="#calender" role="tab" aria-controls="pills-timeline" aria-selected="true">{{__('Calender')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#courses" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Courses')}}</a>
                </li>
            </ul>
        </div>
        <div class="col-12">
            <div class="card" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px;">
                <div class="card-body">
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane active" id="calender" role="tabpanel">
                           @include('front.instructor._partials.components.calendar')
                        </div>
                        <div class="tab-pane" id="courses" role="tabpanel">
                            @include('front.instructor._partials.components.courses')
                        <div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
@yield('_scripts')
@endsection
