<h4 class="title mb-3">Agents List</h4>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agencyAgent)
            <tr data-row="{{$agencyAgent->id}}">
                <td>
                    <div class="d-flex no-block align-items-center">
                            <div class="m-r-10"><img src="{{$agencyAgent->avatar}}" alt="user" class="rounded-circle" width="45"></div>
                            <div class="">
                                <h5 class="m-b-0 font-16 font-medium">{{$agencyAgent->name}}</h5>
                                <span>{{$agencyAgent->email}}</span>
                        </div>
                    </div>
                </td>

                <td>
                    <select>
                        @foreach($roles as $rol)
                            <option onclick='app.rolPrivileges(this)'
                                    value='{{$rol}}' data-id='{{$agencyAgent->id}}'
                                    data-route='{{route('school.agent.agency.rolPrivileges', [$school, $agency])}}'
                                    {{$agencyAgent->roles == $rol ? 'selected' : ''}}
                            >
                                {{$rol}}
                            </option>
                        @endforeach
                    </select>
                </td>

                <td>

                @if (!$agencyAgent->active)
                    
                    <a href="javascript:void(0)" data-container="body" title="Resend Activation Email" data-toggle="popover" data-placement="right" data-trigger="focus" data-content="This account is still inactive.<br />
                    <a href='javascript:void(0)' onclick='app.resendActivationEmail(this)' data-id='{{$agencyAgent->id}}' >Resend Activation Email</a>" class="fas fa-user-circle text-warning control-icon active-icon" data-agent-container="{{$agencyAgent->id}}"></a>
                
                @else
                
                <span class="fas fa-user-circle text-success control-icon"></span>
                
                @endif    
                
{{--                @if ($agencyAgent->is_admin)--}}

{{--                <a href="javascript:void(0)" data-container="body" title="Revoke admin privileges" data-toggle="popover" data-placement="right" data-trigger="focus" data-content="This agent is an administrator </br><a href='javascript:void(0)' onclick='app.toggleAdminPrivileges(this)' data-id='{{$agencyAgent->id}}'>Revoke admin privileges</a>" class="fas fa-star text-success control-icon admin-icon" data-agent-container="{{$agencyAgent->id}}" data-is-admin=1></a>--}}

{{--                @else--}}

{{--                <a href="javascript:void(0)" data-container="body" title="Grant admin privileges" data-toggle="popover" data-placement="right" data-trigger="focus" data-content="This agent is not an administrator</br><a href='javascript:void(0)' onclick='app.toggleAdminPrivileges(this)' data-id='{{$agencyAgent->id}}'>Grant admin privileges</a>" class="fas fa-star text-muted control-icon admin-icon" data-agent-container="{{$agencyAgent->id}}" data-is-admin=0></a>--}}
{{--                @endif--}}

                @if (auth()->guard('agent')->user()->is_admin && auth()->guard('agent')->user()->id != $agencyAgent->id )

                    <a href="javascript:void(0)" data-container="body" title="Delete Agent" data-toggle="popover" data-placement="right" data-trigger="focus" data-content="Are you sure you want to delete this agent?</br><a href='javascript:void(0)' onclick='app.deleteAgent(this)' data-id='{{$agencyAgent->id}}'>Yes, Delete</a>" class="fas fa-times text-danger control-icon delete-icon" data-agent-container="{{$agencyAgent->id}}" data-is-admin=0></a>
                    
                @endif

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>