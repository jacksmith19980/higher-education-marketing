@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => $permissions['create|stage'],
    'title'=> 'Application Statuses'
])

@section('table-content')

    <table id="index_table" class="table table-bordered new-table nowrap display">
        <thead>
        <tr>
        <!-- <th class="control-column">{{__('Actions')}}</th> -->
            <th>{{__('Status')}}</th>
            <th>{{__('Label')}}</th>
            <th>ID</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        @if ($statuses)
            @foreach ($statuses as $status)
                <tr data-status-id="{{$status->id}}">
                    <td>{{$status->title}}</td>
                    <td>{{$status->label}}</td>
                    <td class="small-column">{{$status->id}}</td>
                    @php
                        $buttons['buttons']['edit'] = [
                            'text' => 'Edit',
                            'icon' => 'icon-note',
                            'attr' => "onclick=app.redirect('".route('applicationStatus.edit' , $status)."')",
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('stage', 'edit', $status),
                        ];
                        $buttons['buttons']['delete'] = [
                            'text' => 'Delete',
                            'icon' => 'icon-trash text-danger',
                            'attr' => 'onclick=app.deleteElement("'.route('applicationStatus.destroy' , $status).'","","data-status-id")',
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('stage', 'delete', $status),
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
