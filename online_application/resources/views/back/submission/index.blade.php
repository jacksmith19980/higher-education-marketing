@extends('back.layouts.core.helpers.table', [
    'show_buttons' => false,
    'title'=> __('Submissions Status'),
])

@section('table-content')
<div class="pb-2 row" id="datatableNewFilter">
    <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
    </div>
    <div class="col-md-8 d-flex justify-content-end">
        <div class="mr-3 d-flex" id="calContainer" style="flex:1;">
            <div class="input-group date">
                <input id="calendarRanges" type="text" class="form-control calendarRanges" autocomplete="off">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <span class="ti-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <button id="panel-toggle" type="button" class="mr-3 btn btn-info add_field_toggle">
            <i class="fas fa-sliders-h"></i> {{__('Filters')}}
        </button>
        <button id="clear_filter" class="mr-3 btn btn-light text-danger" href="javascript:void(0)">{{__('Clear All')}}</button>
        <div class="d-flex justify-content-end" id="filterContainer">
            <div class="btn-group">
            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                {{__('Actions')}}
            </button>
            <div class="dropdown-menu">
                @if(PermissionHelpers::checkActionPermission('submission' , 'create'))
                    <a
                        id="show-archived"
                        class="dropdown-item"
                        href="javascript:void(0)"
                        onclick="app.addApplicationSubmission(this , null)"
                        data-title="{{__('Add Application Submission')}}"
                        data-route="{{route('submissions.add.new' , ['student' => null ])}}"
                    >
                        {{__('Add New Submission')}}
                    </a>
                @endif
                <a
                    id="show-archived"
                    class="dropdown-item"
                    href="javascript:void(0)"
                    onclick="app.showArchived()"
                >
                    {{__('Show Archived')}}
                </a>
                <a
                    id="show-unarchived"
                    class="dropdown-item hide"
                    href="javascript:void(0)"
                    onclick="app.hideArchived()"
                >
                    {{__('Show Active')}}
                </a>
                <a
                    class="dropdown-item"
                    href="javascript:void(0)"
                    onclick="app.exportSubmissions(this)"
                    data-title="{{__('Export Excel File')}}"
                    data-route={{route('submissions.select.excel')}}
                    data-file={{'excel'}}
                >
                    {{__('Export Excel')}}
                </a>
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="app.exportSubmissions(this)"
                    data-title="{{__('Export CSV File')}}"
                    data-route={{route('submissions.select.excel')}}
                    data-file={{'csv'}}
                >
                    {{__('Export CSV')}}
                </a>
                <a
                    class="dropdown-item"
                    href="javascript:void(0)"
                    onclick="app.reorderColumns()"
                >
                    {{__('Edit Column Order')}}
                </a>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="table-responsive"> -->
<table
    id="submissions_table"
    data-route="{{route('submissions.getSubmissions')}}"
    data-archived="false"
    data-i18n="{{$datatablei18n}}"
    class="table table-bordered new-table nowrap display"
>
    <thead>
        <tr>
            <th></th>
            <th>
                @include('back.layouts.core.helpers.bulk-actions' , [
                    'buttons' => [
                        'edit' => [
                            'action'            => "onclick=app.bulkEdit('".route('submissions.bulk.edit')."','Edit&nbsp;Submissions')",
                            'icon'              => 'fas fa-pencil-alt',
                            'title'             => __("Edit Submissions"),
                            'allowed'           => PermissionHelpers::checkActionPermission('submission' , 'edit')
                        ],
                        'delete' => [
                            'action'            => "onclick=app.bulkDelete('".route('submissions.bulk.destroy')."')",
                            'icon'              => "fas fa-trash-alt text-dange",
                            'title'             => __("Delete Submissions"),
                            'allowed'           => PermissionHelpers::checkActionPermission('submission' , 'delete')
                        ],
                    ]
                ])
            </th>
            <th>{{__('Name')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Application')}}</th>
            <th>{{__('Course')}}</th>
            <th>{{__('Program')}}</th>
            <th>{{__('Recent Transaction')}}</th>
            <th>{{__('Application Status')}}</th>
            <th>{{__('Progress Status')}}</th>
            <th>{{__('Contact Type')}}</th>
            <th>{{__('Campus')}}</th>
            <th>{{__('Updated')}}</th>
            <th>{{__('Created')}}</th>
            <th class="control-column"></th>
        </tr>
    </thead>
</table>
<!-- </div> -->

<div id="reorder-columns" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Choose columns')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div>{{__('Options')}}</div>
                        <div class="sortable-options">
                            <div>
                                <input type="checkbox" name="item-3" id="col-3" checked> <label for="col-3">{{__('Email')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-4" id="col-4" checked> <label for="col-4">{{__('Application')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-5" id="col-5" checked> <label for="col-5">{{__('Course')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-6" id="col-6" checked> <label for="col-6">{{__('Program')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-7" id="col-7" checked> <label for="col-7">{{__('Recent Transaction')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-8" id="col-8" checked> <label for="col-8">{{__('Application Status')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-9" id="col-9" checked> <label for="col-9">{{__('Progress Status')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-10" id="col-10" checked> <label for="col-10">{{__('Contact Type')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-11" id="col-11" checked> <label for="col-11">{{__('Campus')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-12" id="col-12" checked> <label for="col-12">{{__('Updated')}}</label>
                            </div>
                            <div>
                                <input type="checkbox" name="item-13" id="col-13" checked> <label for="col-13">{{__('Created')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>{{__('Selected Columns')}}:</div>
                        <div id="name-column" class="sortable-item"><span>{{__('Name')}}</span></div>
                        <ul class="sortable">
                            <li id="item-3" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Email')}} </span> <span class="close" onclick="closeOption(3)">X</span></li>
                            <li id="item-4" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Application')}} </span> <span class="close" onclick="closeOption(4)">X</span></li>
                            <li id="item-5" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Course')}} </span> <span class="close" onclick="closeOption(5)">X</span></li>
                            <li id="item-6" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Program')}} </span> <span class="close" onclick="closeOption(6)">X</span></li>
                            <li id="item-7" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Recent Transaction')}} </span> <span class="close" onclick="closeOption(7)">X</span></li>
                            <li id="item-8" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Application Status')}} </span> <span class="close" onclick="closeOption(8)">X</span></li>
                            <li id="item-9" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Progress Status')}} </span> <span class="close" onclick="closeOption(9)">X</span></li>
                            <li id="item-10" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Contact Type')}} </span> <span class="close" onclick="closeOption(10)">X</span></li>
                            <li id="item-11" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Campus')}} </span> <span class="close" onclick="closeOption(11)">X</span></li>
                            <li id="item-12" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Updated')}} </span> <span class="close" onclick="closeOption(12)">X</span></li>
                            <li id="item-13" class="sortable-item"><span><img src="/assets/images/icons/drag.png" />{{__('Created')}} </span> <span class="close" onclick="closeOption(13)">X</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Close')}}</button>
                <a class="text-white btn btn-default btn-ok">{{__('Save')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('right-panel')
    @include('back.layouts.core.filter.panel')
@endsection
