@extends('back.layouts.core.helpers.table' , [
    'show_buttons' => $permissions['create|user'],
    'title'=> 'Users'
])

@section('table-content')
    <table id="index_table" class="table table-bordered new-table nowrap display">
        <thead>
        <tr>
        <!-- <th class="control-column">{{__('Actions')}}</th> -->
            <th>{{__('Name')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Phone')}}</th>
            <th>{{__('Roles')}}</th>
            <th>{{__('Campuses')}}</th>
            <th>{{__('Active')}}</th>
            <th>ID</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        @if ($users)
            @foreach ($users as $user)
                <tr data-role-id="{{$user->id}}">

                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{$user->rolesList}}</td>
                    <td>
                        {{ implode(', ', array_column($user->campuses , 'title'))}}
                    </td>

                    <td>
                        @if ($user->is_active)
                                <em class="fa fa-circle text-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></em>
                        @else
                                <em class="fa fa-circle text-orange" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pending"></em>
                        @endif
                    </td>

                    <td class="small-column">{{$user->id}}</td>
                    @php
                        $buttons['buttons']['edit'] = [
                            'text' => 'Edit',
                            'icon' => 'icon-note',
                            'attr' => "onclick=app.redirect('".route('users.edit' , $user)."')",
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('user', 'edit', $user)
                        ];
                        $buttons['buttons']['delete'] = [
                            'text' => 'Delete',
                            'icon' => 'icon-trash text-danger',
                            'attr' => 'onclick=app.deleteElement("'.route('users.destroy' , $user).'","","data-role-id")',
                            'class' => '',
                            'show'  => PermissionHelpers::checkActionPermission('user', 'delete', $user)
                        ];

                        if ($user->is_active){
                            $buttons['buttons']['activation'] = [
                                'text' => 'Deactivate',
                                'icon' => 'icon-trash',
                                'attr' => 'data-route=' . route('deactivate.user', $user).' onclick=app.actionUser(this,"deactivate")',
                                'class' => '',
                                'show'  => PermissionHelpers::checkActionPermission('user', 'edit', $user)
                            ];
                        }else{
                            $buttons['buttons']['activation'] = [
                                'text' => 'Activate',
                                'icon' => 'icon-trash',
                                'attr' => 'data-route=' . route('activate.user', $user).' onclick=app.actionUser(this,"activate")',
                                'class' => '',
                                'show'  => PermissionHelpers::checkActionPermission('user', 'edit', $user)
                            ];
                        }
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
