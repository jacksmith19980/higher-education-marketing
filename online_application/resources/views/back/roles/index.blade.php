@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => $permissions['create|settings'],
    'title'=> 'Roles'
])

@section('table-content')

    <table id="index_table" class="table table-bordered new-table nowrap display">
        <thead>
        <tr>
        <!-- <th class="control-column">{{__('Actions')}}</th> -->
            <th>{{__('Roles')}}</th>
            <th>ID</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        @if ($roles)
            @foreach ($roles as $role)
                <tr data-role-id="{{$role->id}}">

                    <td>{{$role->name}}</td>

                    <td class="small-column">{{$role->id}}</td>
                    @php
                        $buttons = [];
                        $buttons['buttons']['edit'] = [
                            'text' => 'Edit',
                            'icon' => 'icon-note',
                            'attr' => "onclick=app.redirect('".route('roles.edit' , $role)."')",
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('settings', 'edit')
                        ];
                        $buttons['buttons']['delete'] = [
                            'text' => 'Delete',
                            'icon' => 'icon-trash text-danger',
                            'attr' => 'onclick=app.deleteElement("'.route('roles.destroy' , $role).'","","data-role-id")',
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('settings', 'delete')
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
