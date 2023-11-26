<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate="" enctype="multipart/form-data">

    <div class="row">

        @csrf

        <input type="hidden" name="action" value="invite_users" />

        @if (isset($users) && $users->count() )
            <div class="table-responsive">
                <table class="table no-wrap v-middle">
                    <thead>
                        <tr class="border-0">
                            <th class="border-0">User</th>
                            <th class="border-0">Role</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr @if($loop->last) data-name="settings_last_invited_user" @endif>
                            <td>
                                <div class="d-flex no-block align-items-center">
                                    <div class="m-r-10"><img src="{{$user->avatar}}" alt="user" class="rounded-circle" width="45">
                                    </div>
                                    <div class="">
                                        <h5 class="font-medium m-b-0 font-16">{{$user->name}}</h5><span>{{$user->email}}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <a href="javascript:void(0)"
                                onclick="app.updateUserRoles(`{{route('user.roles.edit', ['school' => $school, 'user' => $user])}}`, ``, `profile`)">
                                    @if (count($user->roles) > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-success">{{$role->name}}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-info">{{__('No Role assigned')}}</span>
                                    @endif
                                </a>
                            </td>

                            <td>
                                @if ($user->is_active)
                                        <em class="fa fa-circle text-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></em>
                                @else
                                        <em class="fa fa-circle text-orange" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pending"></em>
                                @endif
                            </td>

                            <td @if($loop->last) data-name="settings_last_invited_user_actions" @endif>
                                <a data-route="{{route('delete.user', $user)}}" href="#" onclick="app.actionUser(this, 'delete')">Delete</a>
                                |
                                @if ($user->is_active)
                                    <a data-route="{{route('deactivate.user', $user)}}" href="#" onclick="app.actionUser(this, 'deactivate')">Deactivate</a>
                                @else
                                    <a data-route="{{route('activate.user', $user)}}" href="#" onclick="app.actionUser(this, 'activate')">Activate</a>
                                @endif
                                |
{{--                                <a data-route="{{route('roles.userRoles', $user)}}" href="#" onclick="app.userRoles(this)">Roles</a>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <h4>{{__('Invite Users')}}</h4>
    @include('back.schools._partials.invite-users')
    <div class="col-md-12">
        <button data-name="settings_users_save_button" class="float-right btn btn-success" onclick="app.addUser({{session('tenant')}})">{{__('Invite Users')}}</button>
    </div>
    <div id="users_settings_div"></div>
</form>
