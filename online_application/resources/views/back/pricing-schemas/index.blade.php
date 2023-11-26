@extends('back.layouts.core.helpers.table', [
    'show_buttons' => $permissions['create|pricing'],
    'title' => 'Pricing Schemas',
])

@section('table-content')
    <div class="row pb-2" id="datatableNewFilter">
        <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
        </div>
    </div>
    <table id="applicant_table" class="table new-table table-bordered table-striped display">
        <thead>
            <tr>
                <th>
                    @include('back.layouts.core.helpers.bulk-actions', [
                        'delete' => [
                            'route' => route('pricing.bulk.destroy'),
                            'reloadOnDelete' => true,
                            'allowed' => PermissionHelpers::checkActionPermission('pricing', 'delete'),
                        ],
                        'buttons' => [],
                    ])
                </th>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Active') }}</th>
                <th>{{ __('Created At') }}</th>
                <th class="control-column"></th>
            </tr>
        </thead>

        <tbody>

            @if ($pricingSchemas)
                @foreach ($pricingSchemas as $pricingSchema)
                    <tr data-pricing-chema-id="{{ $pricingSchema->id }}">
                        <td>
                            <div>
                                <span class="mr-5">
                                    <input type="checkbox" name="multi-select" value="{{ $pricingSchema->id }}"
                                        onchange="app.selectRow(this)" />
                                </span>
                            </div>
                        </td>
                        <td>{{ $pricingSchema->id }}</td>
                        <td>
                            <a href="javascript:app.openModal({{ $pricingSchema->id }} , '{{ route('pricing.show', $pricingSchema->id) }} ', '{{ $pricingSchema->title }}')">{{ $pricingSchema->title }}</a>
                        </td>
                        <td>{{ $pricingSchema->description }}</td>
                        <td>{{ $pricingSchema->is_active ? __('Yes') : __('No') }}</td>
                        <td>{{ $pricingSchema->created_at ? $pricingSchema->created_at->format('Y-m-d h:i:s') : '' }}</td>
                        @php
                            $buttons['show'] = [
                                'text' => 'View',
                                'icon' => 'icon-eye',
                                'modal' => [
                                    'id' => $pricingSchema->id,
                                    'route' => route('pricing.show', $pricingSchema->id),
                                    'title' => $pricingSchema->title
                                ],
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('pricing', 'view', $pricingSchema->id),
                            ];
                            
                            $buttons['edit'] = [
                                'text' => 'Edit',
                                'icon' => 'icon-note',
                                'attr' => "onclick=app.redirect('" . route('pricing.edit', $pricingSchema->id) . "')",
                                'class' => 'dropdown-item',
                                'show' => PermissionHelpers::checkActionPermission('pricing', 'edit', $pricingSchema),
                            ];
                            
                            $buttons['delete'] = [
                                'text' => 'Delete',
                                'icon' => 'icon-trash text-danger',
                                'attr' => 'onclick=app.deleteElement("' . route('pricing.destroy', $pricingSchema) . '","","data-pricing-chema-id")',
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('pricing', 'delete', $pricingSchema),
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
@endsection
