@extends('front.layouts.agents')
@section('content')

    <div class="page-wrapper" style="padding-top: 100px;">

        <div class="container-fluid">
            <div class="sec-app-dashboard">
                <div class="agent-dashboard-container px-2 py-4 p-md-4 mb-4 box-shadow ">
                    <div class="col-md-12">
                        <h3 class="app-col-title text-primary my-4 mb-md-2">{{__('Invoices and Payments')}}</h3>
                        <div id="lang_opt_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">


    <div class="d-md-flex align-items-end mr-3">
        <div class="ml-auto m-b-20 d-flex no-block align-items-end">
        </div>
    </div>

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
    <table id="accounting_table" data-route="{{route('school.agent.getInvoicePayment', $school)}}" data-i18n="{{$datatablei18n}}"
           class="table new-table table-bordered nowrap display" data-school="{{$school->slug}}">

        <thead>

        <tr>
        <!-- <th class="control-column">{{__('Actions')}}</th> -->
            <th>{{__('Date')}}</th>
            <th>{{__('Type')}}</th>
            <th>{{__('No.')}}</th>
            <th>{{__('Student')}}</th>
            <th>{{__('Balance')}}</th>
            <th>{{__('Total')}}</th>
            <th>{{__('Status')}}</th>
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