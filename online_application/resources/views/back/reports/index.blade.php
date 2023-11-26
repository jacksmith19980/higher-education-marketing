@extends('back.layouts.core.helpers.table', [
    'show_buttons' => $permissions['create|report'],
    'title' => 'Reports',
])

@section('table-content')
    <div class="row pb-2" id="datatableNewFilter">
        <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
        </div>
        <div class="col-lg-6 col-md-5 col-12 d-flex" id="calContainer">
        </div>
        <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end" id="filterContainer">
        </div>
    </div>
    <table id="report_list_table" class="table new-table table-bordered table-striped display">
        <thead>
            <tr>
                <th>
                    @include('back.layouts.core.helpers.bulk-actions' , [
                        'buttons' => [],
                        'delete' => [
                                'route' => route('reports.bulk.destroy'),
                                'reloadOnDelete' => true,
                                'allowed'   => PermissionHelpers::checkActionPermission('report' , 'delete')
                            ]
                    ])
                </th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('ID') }}</th>
                <th class="control-column"></th>

            </tr>

        </thead>

        <tbody>

            @if ($reports)
                @foreach ($reports as $report)

                    <tr data-report-id="{{ $report->id }}">
                        <td>
                            <div>
                                <span class="mr-5">
                                    <input type="checkbox" name="multi-select" value="{{$report->id}}" onchange="app.selectRow(this)" />
                                </span>
                            </div>                        
                        </td>
                        <td><a
                                href="{{ route('reports.show', $report->id) }}">{{ $report->name }}</a>
                        </td>
                        <td>{{ $report->category->name }}</td>
                        <td>{{ $report->id }}</td>
                        </td>


                        @php
                            $buttons['buttons']['view'] = [
                                'text' => 'View',
                                'icon' => 'icon-eye',
                                'attr' => "onclick=app.redirect('" . route('reports.show', $report->id) . "')",
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('report', 'view', $report->id),
                            ];
                            
                            $buttons['buttons']['edit'] = [
                                'text' => 'Edit',
                                'icon' => 'icon-note',
                                'attr' => "onclick=app.redirect('" . route('reports.edit', $report->id) . "')",
                                'class' => 'dropdown-item',
                                'show' => PermissionHelpers::checkActionPermission('report', 'edit', $report),
                            ];
                            
                            $buttons['buttons']['delete'] = [
                                'text' => 'Delete',
                                'icon' => 'icon-trash text-danger',
                                'attr' => 'onclick=app.deleteElement("' . route('reports.destroy', $report) . '","","data-report-id")',
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('report', 'delete', $report),
                            ];
                            
                        @endphp

                        <td class="control-column cta-column">
                            @include('back.layouts.core.helpers.table-actions-permissions', $buttons)
                        </td>

                    </tr>
                @endforeach
            @endif
        </tbody>

    </table>

@endsection
