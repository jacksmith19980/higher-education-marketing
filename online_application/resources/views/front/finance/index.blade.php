@extends('front.layouts.minimal')
@section('content')

<div class="page-wrapper" style="padding-top: 100px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
            	<div class="card">
				    <div class="card-header" style="background-color: #fff;">
				        <h4 class="">{{__('Invoices')}}</h4>
				    </div>
				</div>
				<div class="card">
					<div class="card-body">
		            	<div class="row" id="datatableNewFilter">
				            <div class="col-md-6 col-sm-4 col-xs-12" id="lenContainer">
				            </div>
				            <div class="col-md-3 col-sm-4 col-xs-12" id="calContainer">
				                 <!-- <div class="input-group mr-3">
				                    <input id="calendarRanges" type="text" class="form-control calendarRanges">
				                    <div class="input-group-append">
				                        <span class="input-group-text">
				                            <span class="ti-calendar"></span>
				                        </span>
				                    </div>
				                </div> -->
				            </div>
				            <div class="col-md-3 col-sm-4 col-xs-12" id="filterContainer">
				            </div>
				        </div>
				        <input type="hidden" name="student_id" id="student_id" value="{{$student->id}}">
		            	<div class="table-responsive">
				            <table id="accounting_student_table" data-route="{{route('finance.getInvoicePayment', $school)}}"
				                   class="table table-striped table-bordered new-table nowrap display" style="width: 100%;">

				                <thead>
				                    <tr>
				                        <th>{{__('Date')}}</th>
				                        <th>{{__('Type')}}</th>
				                        <th>{{__('No.')}}</th>
				                        <th>{{__('Balance')}}</th>
				                        <th>{{__('Total')}}</th>
				                        <th>{{__('Invoice Status')}}</th>
				                        <!-- <th></th> -->
				                    </tr>
				                </thead>
				            </table>

				        </div>
				    </div>
				</div>
            </div>
         </div>
     </div>


@endsection