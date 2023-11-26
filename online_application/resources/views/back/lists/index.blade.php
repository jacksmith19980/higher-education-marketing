@extends('back.layouts.core.helpers.table', [
    'show_buttons' => $permissions['create|liste'],
    'title' => 'Data Lists',
])

@section('table-content')
    <div class="row pb-2" id="datatableNewFilter">
        <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
        </div>
        <div class="col-lg-6 col-md-5 col-12 d-flex" id="calContainer">
            <div class="input-group">
                <input id="date_range" autocomplete="off" type="text" class="form-control">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <span class="ti-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <table id="lists_table" class="table new-table table-bordered table-striped display">
        <thead>
            <tr>
                <th>
                    @include('back.layouts.core.helpers.bulk-actions', [
                        'delete' => [
                            'route' => route('lists.bulk.destroy'),
                            'reloadOnDelete' => true,
                            'allowed' => PermissionHelpers::checkActionPermission('lists', 'delete'),
                        ],
                        'buttons' => [],
                    ])
                </th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Count') }}</th>
                <th>{{ __('Active') }}</th>
                <th>{{ __('Created At') }}</th>
                <th class="control-column"></th>
            </tr>
        </thead>

        <tbody>

            @if ($lists)
                @foreach ($lists as $list)
                    <tr data-list-id="{{ $list->id }}">
                        <td>
                            <div>
                                <span class="mr-5">
                                    <input type="checkbox" name="multi-select" value="{{ $list->id }}"
                                        onchange="app.selectRow(this)" />
                                </span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('lists.show', $list->id) }}">{{ $list->name }}</a>
                        </td>
                        <td>{{ $list->id }}</td>
                        <td>
                            <a href="{{ route('lists.show', $list->id) }}">{{ $list->row_count }}</a>
                        </td>
                        <td>
                            @include('back.layouts.core.forms.switch', [
                                'name' => 'active',
                                'label' => '',
                                'class' => 'switch form-control-sm',
                                'required' => false,
                                'attr' =>
                                    'data-on-text=Yes data-off-text=No onchange=setActive(this) data-active-id=' .
                                    $list->id,
                                'helper_text' => '',
                                'value' => $list->is_active,
                                'default' => true,
                            ])
                        </td>
                        <td>{{ $list->created_at ? $list->created_at->format('Y-m-d h:i:s') : '' }}</td>
                        @php
                            $buttons['show'] = [
                                'text' => 'View',
                                'icon' => 'icon-eye',
                                'modal' => [
                                    'id' => $list->id,
                                    'route' => route('lists.show', $list->id),
                                    'title' => $list->title,
                                ],
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('lists', 'view', $list->id),
                            ];
                            
                            $buttons['edit'] = [
                                'text' => 'Edit',
                                'icon' => 'icon-note',
                                'attr' => "onclick=app.redirect('" . route('lists.edit', $list->id) . "')",
                                'class' => 'dropdown-item',
                                'show' => PermissionHelpers::checkActionPermission('lists', 'edit', $list),
                            ];
                            
                            $buttons['delete'] = [
                                'text' => 'Delete',
                                'icon' => 'icon-trash text-danger',
                                'attr' => 'onclick=app.deleteElement("' . route('lists.destroy', $list) . '","","data-list-id")',
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('lists', 'delete', $list),
                            ];
                        @endphp
                        <td class="control-column cta-column">
                            @include('back.layouts.core.helpers.table-actions-permissions-2', [
                                'buttons' => $buttons,
                            ])
                        </td>

                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <style>
        td>.form-group {
            margin-bottom: 0 !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        var setActive = (el) => {
            id = $(el).data('active-id');
            val = el.checked;

            var data = {
                action: 'liste.setActive',
                payload: {
                    id,
                    val
                }
            };

            app.appAjax('POST', data, app.ajaxRoute).then(data => {
                if (data.response == 'error' && data.status != 200) {
                    // $(el).checked = !val;
                    // $(el).trigger('change')
                }
            })
        }

        app.dateRange = function() {
            if ($('#date_range').length > 0) {
                $('#date_range').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')],
                        'This Year': [moment().startOf('year'), moment().endOf('year')],
                        'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year')
                            .endOf('year')
                        ]

                    },
                    alwaysShowCalendars: true,
                    opens: 'left',
                    separator: ' to ',
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment(),
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });

                $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                    var startDate = picker.startDate.format('YYYY-MM-DD 00:00:00');
                    var endDate = picker.endDate.format('YYYY-MM-DD 23:59:59');
                    var data = {
                        action: 'lists.index',
                        payload: {
                            filterBy: 'date',
                            startDate: startDate,
                            endDate: endDate
                        }
                    };
                    
                    let url = new URL(window.location.href);
                    url.searchParams.set("date", document.querySelector('#date_range').value);
                    window.location.href = url.href;
                });
            }

        };

        app.dateRange();
    </script>
@endsection
