@extends('back.layouts.core.helpers.table',[
'show_buttons' => true,
'show_login_button' => true,
'title' => 'Recruiters Hub',
])

@section('table-content')
<div class="row mt-2">
    <div class="col-md-1" style="padding-left: 24px;">
        <div class="form-group" style="width: 80px">
            <select class="form-control" id="length_menu">
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="150">150</option>
            </select>
        </div>
    </div>
    <div class="col-md-6 custom-search-bar">
        <div class="form-group" style="padding-left: 12px;">
            <input type="text" class="form-control searchicon" id="search_box" placeholder="Search">
        </div>
    </div>
    <div class="col-md-5" style="padding-right: 24px;">
        <div class="btn-group float-right">
            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" 
            style="border: 1px solid var(--color-ablue); color: var(--color-ablue); background-color: transparent; height: 35px;">
                Actions
            </button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                <a class="dropdown-item" href="javascript:void(0)" onclick="exportAgencies(this)" data-title="Export Agencies Excel File" data-route="{{route('agencies.download.excel')}}" data-file="excel">
                    Export Excel
                </a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="exportAgencies(this)" data-title="Export Agencies CSV File" data-route="{{route('agencies.download.excel')}}" data-file="csv">
                    Export CSV
                </a>
            </div>
        </div>
    </div>
</div>
<table id="agencies_index_table" class="table table-striped table-bordered new-table display">
    <thead>
        <tr>
            <th class="hidding no-sort"></th>
            <th class="all text-left no-sort" style="width: 115px!important">
                <div class="btn-group" style="display: inline-flex!important;">
                    <div class="btn btn-light">
                        <input style="width: 15px; height: 15px;" type="checkbox" id="select_all" name="select_all" value="" onchange="app.toggleSelectAll(this)"/>
                    </div>
                    <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split toggle-bulk-actions"
                            id="toggle-bulk-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item bulk-action-item" href="javascript:void(0)"
                            onclick="bulkDeleteAgencies()">
                            <i class="fas fa-trash-alt mr-1 text-danger"></i> {{__('Delete Selected')}}
                        </a>
                    </div>
                </div>
            </th>
            <th class="all">{{__('Agency')}}</th>
            <th>{{__('Students')}}</th>
            <th>{{__('Agents')}}</th>
            <th>{{__('Status')}}</th>
            <th>ID</th>
            <th class="no-sort all"></th>
        </tr>
    </thead>
    <tbody>
        @if (isset($agencies))
        @foreach ($agencies as $agency)
        <tr data-agency-id="{{$agency->id}}">
            <td>{{$agency->id}}</td>
            <td class="text-left" style="padding-left: 39px;"><input type="checkbox" name="multi-select" 
                value="{{$agency->id}}" style="width: 15px; height: 15px;" onchange="checkSelectAll()" /></td>
            <td>
                <a href="{{route('agencies.show' , $agency)}}">{{$agency->name}}</a>
                <p><small class="text-muted">{{$agency->email}}</small></p>
                <span style="display: none">
                    @foreach ($agency->agents as $agent)
                        <h5> {{$agent->email}} </h5>
                    @endforeach
                </span>
            </td>
            <td>
                <h5><i class="mdi mdi-school"></i> {{$agency->students()->count()}} </h5>
            </td>
            <td>
                <h5><i class="mdi mdi-account-multiple"></i> {{$agency->agents()->count()}} </h5>
            </td>
            <td class="small-column approve-bullet">

                <i style="cursor: pointer" onclick="app.toggleAgencyStatus(this)" data-agency-id='{{$agency->id}}'
                    class="approved fa fa-circle text-success {{(!$agency->approved) ? 'hidden' : ''}}"
                    data-toggle="tooltip" data-placement="top" data-code-id="1" title=""
                    data-original-title="Approved"></i>

                <i style="cursor: pointer" onclick="app.toggleAgencyStatus(this)" data-agency-id='{{$agency->id}}'
                    class="unapporved fa fa-circle text-danger {{($agency->approved) ? 'hidden' : ''}}"
                    data-toggle="tooltip" data-placement="top" data-code-id="1" title=""
                    data-original-title="Unapproved"></i>

            </td>

            <td class="small-column">{{$agency->id}}</td>

            <td class="control-column cta-column">
                @include('back.layouts.core.helpers.table-actions' , [
                'buttons'=> [
                'edit' => [
                'text' => 'Edit',
                'icon' => 'icon-note',
                'attr' => "onclick=app.redirect('".route('agencies.edit' , $agency)."')",
                'class' => '',
                ],

                'delete' => [
                'text' => 'Delete',
                'icon' => 'icon-trash text-danger',
                'attr' => 'onclick=app.deleteElement("'.route('agencies.destroy' , $agency).'","","data-agency-id")',
                'class' => '',
                ],

                'status' => [
                'text' => ($agency->approved) ? 'Unapprove' : 'Approve',

                'icon' => ($agency->approved) ? 'icon-close text-danger' : 'icon-check text-success',

                'attr' => 'onclick=app.toggleAgencyStatus(this) data-agency-id=' . $agency->id,
                'class' => ($agency->approved) ? 'approved' : 'unapporved',
                ],
                ]
                ])
            </td>
        </tr>
        @endforeach
        @endif

        @if (isset($params['remoteAgencies']))
        @foreach ($params['remoteAgencies'] as $agency)
        <tr>
            <td><a href="">{{$agency['fields']['core']['companyname']['value']}}</a></td>
            <td class="small-column"></td>
            <td></td>
            <td class="control-column cta-column">@include('back.layouts.core.helpers.table-actions')</td>
        </tr>

        @endforeach
        @endif

    </tbody>
</table>
@endsection
