@extends('back.layouts.core.helpers.table', [
    'show_buttons' => $permissions['create|documentBuilder'],
    'title' => 'Document Builders',
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
    <table id="documentBuilder_list_table" class="table new-table table-bordered table-striped display">
        <thead>
            <tr>
                <th>
                    @include('back.layouts.core.helpers.bulk-actions', [
                        'buttons' => [],
                        'delete' => [
                            'route' => route('documentBuilder.bulk.destroy'),
                            'reloadOnDelete' => true,
                            'allowed' => PermissionHelpers::checkActionPermission('documentBuilder', 'delete'),
                        ],
                    ])
                </th>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Selector') }}</th>
                <th class="control-column"></th>

            </tr>

        </thead>

        <tbody>

            @if ($documentBuilders)
                @foreach ($documentBuilders as $documentBuilder)
                    <tr data-documentBuilder-id="{{ $documentBuilder->id }}">
                        <td>
                            <div>
                                <span class="mr-5">
                                    <input type="checkbox" name="multi-select" value="{{ $documentBuilder->id }}"
                                        onchange="app.selectRow(this)" />
                                </span>
                            </div>
                        </td>
                        <td>{{ $documentBuilder->id }}</td>
                        <td><a
                                href="{{ route('documentBuilder.edit', $documentBuilder->id) }}">{{ $documentBuilder->name }}</a>
                        </td>
                        <td>{{ $documentBuilder->selector }}</td>
                        @php
                            $buttons['buttons']['edit'] = [
                                'text' => 'Edit',
                                'icon' => 'icon-note',
                                'attr' => "onclick=app.redirect('" . route('documentBuilder.edit', $documentBuilder->id) . "')",
                                'class' => 'dropdown-item',
                                'show' => PermissionHelpers::checkActionPermission('documentBuilder', 'edit', $documentBuilder),
                            ];
                            
                            $buttons['buttons']['delete'] = [
                                'text' => 'Delete',
                                'icon' => 'icon-trash text-danger',
                                'attr' => 'onclick=app.deleteElement("' . route('documentBuilder.destroy', $documentBuilder) . '","","data-documentBuilder-id")',
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('documentBuilder', 'delete', $documentBuilder),
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
