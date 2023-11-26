@php

@endphp

@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => false,
    'title'=> 'Attendance',
	'modal'         => "app.attendanceCreate('". route('attendances.add.new') . "' , 'Add New')",
	'button_label'  => 'Add New'
])
@section('table-content')

	<div class="py-3 px-4">
		<div class="p-4 " style="border-left: 10px solid var(--color-primary);background: var(--color-grey-12);border-radius:10px;" >
			<div class="pt-2 pl-2 pr-2">
				<div class="row pb-2" id="datatableNewFilter">
					<div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
					</div>

					<div class="col-lg-6 col-md-5 col-12 d-flex" id="calContainer">
						<div class="input-group date">
							<input id="calendarRanges" type="text" class="form-control calendarRanges">
							<div class="input-group-append">
								<span class="input-group-text"><span class="ti-calendar"></span></span>
							</div>
						</div>

						<div class="d-flex align-items-center">
							<button id="panel-toggle" type="button" class="btn btn-light ml-2">
								<i class="fas fa-sliders-h"></i> {{__('Filters')}}
							</button>
							<a id="clear_filter_attendance" class="ml-2" style="text-decoration: underline;" href="javascript:void(0)">{{__('Clear All')}}</a>
						</div>
					</div>

					<div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end" id="filterContainer">
						<div class="btn-group">
							<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
								aria-expanded="false">
								{{__('Actions')}}
							</button>

							<div class="dropdown-menu">
								<a class="dropdown-item attendance-export" id="attendance-export-excel" href="javascript:void(0)" data-route={{route('attendances.select.excel')}}
								data-file={{'excel'}}>
									{{__('Export Excel')}}
								</a>
								<a class="dropdown-item attendance-export" id="attendance-export-csv" href="javascript:void(0)" data-route={{route('attendances.select.excel')}} data-file={{'csv'}}>
									{{__('Export CSV')}}
								</a>
							</div>
						</div>
					</div>
				</div>

				<table id="attendances_table" data-route="{{route('attendances.getAttendances')}}" data-i18n="{{$datatablei18n}}" class="table table-bordered new-table nowrap display">
					<thead>
					<tr>
						<th>
							@include('back.layouts.core.helpers.bulk-actions' , [
								'buttons' => [

										'edit' => [
										'action'            => "onclick=app.bulkEdit('".route('attendances.bulk.edit')."','Edit&nbsp;Attendances')",

										'icon'              => 'fas fa-pencil-alt',

										'title'             => __("Edit Attendances"),
										'allowed'           => PermissionHelpers::checkActionPermission('attendance' , 'edit')
									],

									'delete' => [
										'action'            => "onclick=app.bulkDelete('".route('attendances.bulk.destroy')."')",
										'icon'              => "fas fa-trash-alt text-dange",
										'title'             => __("Delete Attendances"),
										'allowed'           => PermissionHelpers::checkActionPermission('attendance' , 'delete')
									]
								]
							])
						</th>
						<th>{{__('Student')}}</th>
						<th>{{__('Course')}}</th>
						<th>{{__('Instructor')}}</th>
						<th>{{__('Timeslot')}}</th>
						<th>{{__('Date')}}</th>
						<th>{{__('Status')}}</th>
						<th class="control-column">{{__('Action')}}</th>
					</tr>
					</thead>

				</table>
			</div>
		</div>
	</div>
@endsection

@section('right-panel')
    @include('back.attendances._partials.filter-panel')
@endsection
