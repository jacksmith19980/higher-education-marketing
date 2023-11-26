@extends('back.layouts.core.helpers.table', [
    'show_buttons' => $permissions['create|service'],
    'title' => 'Educational Services',
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
                    @php
                        $buttons = [
                            'buttons'   => [
                                'delete' => [
                                    'route' => route('services.bulk.destroy'),
                                    'reloadOnDelete' => true,
                                    'allowed'   => PermissionHelpers::checkActionPermission('service' , 'delete')
                                ],
                            ]
                        ];
                    @endphp

                    @include('back.layouts.core.helpers.bulk-actions' , [
                        'buttons' => $buttons
                    ])
                </th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Created At') }}</th>
                <th class="control-column"></th>

            </tr>

        </thead>

        <tbody>

            @if ($educationalServices)
                @foreach ($educationalServices as $educationalService)
                    <tr data-service-id="{{ $educationalService->id }}">
                        <td>
                            <div>
                                <span class="mr-5">
                                    <input type="checkbox" name="multi-select" value="{{$educationalService->id}}" onchange="app.selectRow(this)" />
                                </span>
                            </div>
                        </td>
                        <td><a
                                href="{{ route('services.edit', $educationalService->id) }}">{{ $educationalService->name }}</a>
                        </td>
                        <td>{{ $educationalService->description }}</td>
                        <td>{{ $educationalService->amount }}</td>
                        <td>{{ $educationalService->educationalServiceCategory()->first() ? $educationalService->educationalServiceCategory()->first()->name : '' }}</td>
                        <td>{{ $educationalService->created_at ? $educationalService->created_at->format('Y-m-d h:i:s') : '' }}
                        </td>


                        @php
                            $buttons['buttons']['view'] = [
                                'text' => 'View',
                                'icon' => 'icon-eye',
                                'attr' => "onclick=app.redirect('" . route('services.edit', $educationalService->id) . "')",
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('service', 'view', $educationalService->id),
                            ];

                            $buttons['buttons']['edit'] = [
                                'text' => 'Edit',
                                'icon' => 'icon-note',
                                'attr' => "onclick=app.redirect('" . route('services.edit', $educationalService->id) . "')",
                                'class' => 'dropdown-item',
                                'show' => PermissionHelpers::checkActionPermission('service', 'edit', $educationalService),
                            ];

                            $buttons['buttons']['delete'] = [
                                'text' => 'Delete',
                                'icon' => 'icon-trash text-danger',
                                'attr' => 'onclick=app.deleteElement("' . route('services.destroy', $educationalService) . '","","data-service-id")',
                                'class' => '',
                                'show' => PermissionHelpers::checkActionPermission('service', 'delete', $educationalService),
                            ];

                        @endphp

                        <td class="control-column cta-column">
                            @include('back.layouts.core.helpers.table-actions-permissions', [
                                ' buttons' =>  $buttons
                            ])
                        </td>

                    </tr>
                @endforeach
            @endif
        </tbody>

    </table>

    <table>
        <tr>
            <td>
                {{-- $students->links() --}}
            </td>
        </tr>
    </table>

@endsection
