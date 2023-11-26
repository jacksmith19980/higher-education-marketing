@extends('front.layouts.agents')
@section('content')

    <div class="page-wrapper" style="padding-top: 100px;">

        <div class="container-fluid">
            <div class="sec-app-dashboard">
                <div class="agent-dashboard-container px-2 py-4 p-md-4 mb-4 box-shadow ">
                    <div class="col-md-12">
                        <h3 class="app-col-title text-primary my-4 mb-md-2">{{__('Application form submissions')}}</h3>
                        <div id="lang_opt_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">



    <div class="row" id="datatableNewFilter">
        <div class="col-md-6 col-sm-4 col-xs-12" id="lenContainer">
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12" id="calContainer">
            <div class="input-group mr-3">
                <input id="calendarRanges" type="text" class="form-control calendarRanges">
                <div class="input-group-append">
                <span class="input-group-text">
                    <span class="ti-calendar"></span>
                </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12" id="filterContainer">
        </div>
    </div>
    <!-- <div class="table-responsive"> -->
    <table id="submissions_table" data-route="{{route('school.agent.getSubmissions', $school)}}" data-i18n="{{$datatablei18n}}"
           class="table table-bordered new-table nowrap display" data-school="{{$school->slug}}">

        <thead>

        <tr>
            <th>{{__('Name')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Student Stage')}}</th>
            <th>{{__('Application')}}</th>
            <th>{{__('Application Status')}}</th>
            <th>{{__('Payment Status')}}</th>
            <th>{{__('Updated')}}</th>
            <th>{{__('Created')}}</th>
            <th class="control-column"></th>
        </tr>
        </thead>
    </table>
    <!-- </div> -->



                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
