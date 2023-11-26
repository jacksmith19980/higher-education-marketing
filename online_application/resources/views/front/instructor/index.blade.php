@extends('front.layouts.instructors')

@section('content')
    <div class="page-wrapper" style="padding-top: 100px;">
        <div class="container-fluid">
            <div class="row">
                @include('front.instructor.dashboard.index')
            </div>
        </div>
    </div>
@endsection
