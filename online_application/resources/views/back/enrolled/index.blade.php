@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => $permissions['create|student'],
    'title'        => 'Students',
])

@section('table-content')
    <div class="row pb-2" id="datatableNewFilter">
        <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
        </div>
        <div class="col-lg-6 col-md-5 col-12 d-flex" id="calContainer">
            <div class="input-group date">
                <input id="calendarRanges" type="text" class="form-control calendarRanges">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <span class="ti-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <button id="panel-toggle" type="button" class="btn btn-light ml-2">
                    <i class="fas fa-sliders-h"></i> {{__('Filters')}}
                </button>
                <a id="clear_student_filter" class="ml-2" style="text-decoration: underline;" href="javascript:void(0)">{{__('Clear All')}}</a>
            </div>

        </div>
        <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end" id="filterContainer">
            <div class="btn-group">
                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    {{__('Actions')}}
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0)"
                        onclick="app.exportStudents(this)"
                            data-title="{{__('Export Excel File')}}"
                            data-route={{route('students.download.excel')}}
                            data-file={{'xlsx'}}>
                                    {{__('Export Excel')}}
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)"
                        onclick="app.exportStudents(this)"
                            data-title="{{__('Export CSV File')}}"
                            data-route={{route('students.download.excel')}}
                            data-file={{'csv'}}>
                                    {{__('Export CSV')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <table id="students_table" data-route="{{route('students.enrolled.list')}}" class="table table-striped table-bordered new-table display">
        <thead>
        <tr>
            <th>
                @include('back.layouts.core.helpers.bulk-actions' ,
                [
                    "buttons" => [
                        'delete' => [
                            'action'     => "onclick=app.bulkDelete('".route('students.bulk.destroy')."')",
                            'icon'       => "fas fa-trash-alt text-dange",
                            'title'      => __("Delete Students"),
                            'allowed'    => PermissionHelpers::checkActionPermission('student' , 'delete')
                        ],
                    ]
                ])
            </th>

            <th>{{__('Id')}}</th>

            <th>{{__('Name')}}</th>

            <th>{{__('Email')}}</th>

            <th>{{__('Course')}}</th>

            <th>{{__('Cohort')}}</th>

            <th>{{__('Program')}}</th>

            <th>{{__('Start Date')}}</th>

            <th>{{__('End Date')}}</th>

            <th>{{__('Created')}}</th>

            <th></th>
        </tr>
        </thead>
    </table>
@endsection

@section('right-panel')
@include('back.layouts.core.filter.students-panel')
@endsection
