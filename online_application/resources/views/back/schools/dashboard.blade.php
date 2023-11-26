@extends('back.layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row controlled-gutter draggable-columns" id="draggable-columns">
        @include('back.schools.dashboard.total-application-graph')
        @include('back.schools.dashboard.sis')
        @include('back.schools.dashboard.contacts')
        @include('back.schools.dashboard.recent-submissions')
        @include('back.schools.dashboard.total-applications')
        @include('back.schools.dashboard.application-pipeline')
        @include('back.schools.dashboard.recent-transactions')

        {{-- <div class="col-lg-4 col-md-6 col-sm-12">--}}
            {{-- @include('back.schools.dashboard.applications-widget')--}}
            {{-- </div>--}}
        {{-- <div class="col-lg-4 col-md-6 col-sm-12">--}}
            {{-- @include('back.schools.dashboard.recent-applicants')--}}
            {{-- @include('back.schools.dashboard.call-back-requests')--}}
            {{-- </div>--}}
        {{-- --}}
        {{-- <div class="col-lg-4 col-md-6 col-sm-12">--}}
            {{-- @include('back.schools.dashboard.applications-stats')--}}
            {{-- </div>--}}
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('media/js/back/main-dashboard.js') }}"></script>
@endsection
