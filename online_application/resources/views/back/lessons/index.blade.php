@extends('back.layouts.core.helpers.table' , [
    'show_buttons'  => false,
    'title'         => 'Lessons',
    'specialButton' => [
        'link'          => 'javascript:void(0)',
        'attr'          =>   "onclick=app.redirect('".route('lessons.createMulti')."')",
        'button_label'  => "Add Multiple Lessons"
    ],
])

@section('table-content')
    <div class="row pb-2" id="datatableNewFilter">
        <div class="col-lg-4 col-md-4 col-12 d-flex" id="lenContainer">
        </div>
        <div class="col-lg-5 col-md-5 col-12 d-flex" id="calContainer">
            <div class="input-group mr-3 date">
                <input id="calendarRanges" type="text" class="form-control calendarRanges">
                <div class="input-group-append">
                <span class="input-group-text">
                    <span class="ti-calendar"></span>
                </span>
                </div>
            </div>
            <button id="panel-toggle" type="button" class="btn btn-light">
                <i class="fas fa-sliders-h"></i> {{__('Filters')}}
            </button>

            <a class="btn btn-light ml-3" id="clear_filter_lesson" href="javascript:void(0)">{{__('Clear All')}}</a>
        </div>
        <div class="col-lg-3 col-md-3 col-12 d-flex justify-content-end" id="filterContainer">

            <div class="btn-group">
                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item lessons-export" href="javascript:void(0)" id="lessons-export-excel" data-route={{route('lessons.select.excel')}} data-file={{'excel'}}>{{__('Export Excel')}}</a>
                    <a class="dropdown-item lessons-export" href="javascript:void(0)" id="lessons-export-csv" data-route={{route('lessons.select.excel')}} data-file={{'csv'}}>{{__('Export CSV')}}</a>
                </div>
            </div>
        </div>
    </div>

    <table id="lessons_table" data-route="{{route('lessons.getLessons')}}" data-i18n="{{$datatablei18n}}"
        class="table table-bordered new-table nowrap display">
        <thead>

        <tr>
            <th>
            @include('back.layouts.core.helpers.bulk-actions' , [
                    'buttons'         => [
                        'edit-semester' => [
                            'action'            => "onclick=app.bulkEdit('".route('lessons.bulk.edit',['action'=>'editSemester'])."','Assign&nbsp;to&nbsp;a&nbsp;Semster')",
                            'icon'              => 'fas fa-users',
                            'title'             => __("Assign to a semster"),
                            'allowed'           => PermissionHelpers::checkActionPermission('lesson' , 'edit')
                        ],

                        {{--  'edit-cohorts' => [
                            'action'            => "onclick=app.bulkEdit('".route('lessons.bulk.edit')."','Edit&nbsp;lessons&nbsp;cohorts')",
                            'icon'              => 'fas fa-users',
                            'title'             => __("Edit Cohorst"),
                            'allowed'           => PermissionHelpers::checkActionPermission('lesson' , 'edit')
                        ],  --}}
                        'edit' => [
                            'action'            => "onclick=app.bulkEdit('".route('lessons.bulk.edit')."','Edit&nbsp;lessons')",

                            'icon'              => 'fas fa-pencil-alt',

                            'title'             => __("Edit Lessons"),
                            'allowed'           => PermissionHelpers::checkActionPermission('lesson' , 'edit')
                        ],
                        'delete' => [
                            'action'            => "onclick=app.bulkDelete('".route('lessons.bulk.destroy')."')",
                            'icon'              => "fas fa-trash-alt text-dange",
                            'title'             => __("Delete Lessons"),
                            'allowed'           => PermissionHelpers::checkActionPermission('lesson' , 'delete')
                        ],
                    ]
                ])
            </th>
            {{--  <th>{{__('Title')}}</th>  --}}
            <th>{{__('Course')}}</th>
            <th>{{__('Instructor')}}</th>
            <th>{{__('Classroom')}}</th>
            <th>{{__('Date')}}</th>
            <th>{{__('Start Time')}}</th>
            <th>{{__('End Time')}}</th>
            <th>{{__('Held')}}</th>
            <th>{{__('Cohorts')}}</th>
            <th>{{__('Students')}}</th>
            <th class="control-column"></th>
        </tr>
        </thead>
    </table>
@endsection

@section('right-panel')
    @include('back.lessons._partials.filter-panel')
@endsection
