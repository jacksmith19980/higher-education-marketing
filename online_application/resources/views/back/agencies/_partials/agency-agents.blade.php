<div class="tab-pane fade" id="agents" role="tabpanel" aria-labelledby="pills-profile-tab">
<div class="card-body">
    
    <h4 class="title m-b-10">{{__('Invite Agents')}}</h4>

         @if (session('success'))             
            <div class="alert alert-success row">{{session('success')}}</div>
         @endif

         <form method="POST" action="{{ route('agencies.inviteAgents' , $agency) }}" class="">
            @method('PUT')
            @csrf
            @include('back.agencies._partials.agency-invite-agents')


            <input type="hidden" name="action" value="invite_agents"  >
            <input type="submit" value="Invite" class="btn btn-success float-right m-r-10" />
        
         </form>

         <div class="clearfix"></div>



    <div class="d-block h-40"></div>
    <br>
    <br>
    @if ($agency->agents()->count())

    <h4 class="title m-b-10">{{__('Agents List')}}</h4>
    <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                       
                        @foreach ($agency->agents as $agencyAgent)
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

{{--                            @if ($agencyAgent->is_admin)--}}
{{--                            --}}
{{--                            <a href="javascript:void(0)" data-container="body" title="Revoke admin privileges"--}}
{{--                               data-toggle="popover" data-placement="right" data-trigger="focus"--}}
{{--                               data-content="This agent is an administrator </br><a href='javascript:void(0)'--}}
{{--                               onclick='app.toggleAdminPrivileges(this)' data-id='{{$agencyAgent->id}}'>Revoke admin privileges</a>"--}}
{{--                               class="fas fa-star text-success control-icon admin-icon"--}}
{{--                               data-agent-container="{{$agencyAgent->id}}" data-is-admin=1>--}}
{{--                            </a>--}}
{{--                            --}}
{{--                            @else--}}

{{--                            <a href="javascript:void(0)" data-container="body" title="Grant admin privileges"--}}
{{--                               data-toggle="popover" data-placement="right" data-trigger="focus"--}}
{{--                               data-content="This agent is not an administrator</br><a href='javascript:void(0)'--}}
{{--                               onclick='app.toggleAdminPrivileges(this)' data-id='{{$agencyAgent->id}}'>Grant admin privileges</a>"--}}
{{--                               class="fas fa-star text-muted control-icon admin-icon"--}}
{{--                               data-agent-container="{{$agencyAgent->id}}" data-is-admin=0>--}}
{{--                            </a>--}}
{{--                            @endif--}}

                        
                            <a href="javascript:void(0)" data-container="body" title="Delete Agent"
                               data-toggle="popover" data-placement="right" data-trigger="focus"
                               data-content="Are you sure you want to delete this agent?</br><a href='javascript:void(0)'
                               onclick='app.deleteAgent(this)' data-id='{{$agencyAgent->id}}'>Yes, Delete</a>"
                               class="fas fa-times text-danger control-icon delete-icon"
                               data-agent-container="{{$agencyAgent->id}}" data-is-admin=0>
                            </a>
                                

                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
         @endif
                @if(session()->get('totalSubmissions-'.$agency->id))
                    @php
                        $totalApplications = session()->get('totalAgencyApplications-'.$agency->id);
                        try {
                            $totalCounts = array_count_values(Arr::flatten( session()->get('totalSubmissions-'.$agency->id) ));
                        } catch(\Exception $e) {
                            $totalCounts = 0;
                        }

                    @endphp
                    {{-- @dump($totalCounts) --}}
                    
                    @if (isset($totalCounts['Submitted']))
                        <h5 class="m-t-30">Compeleted Applications 
                            <span class="pull-right">
                                {{ $submittedPercent = floor( ($totalCounts['Submitted'] / $totalApplications) * 100 ) }}%
                            </span>
                        </h5>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:{{$submittedPercent}}%; height:6px;"></div>
                        </div>
                    @endif
                    
                    
                    @if (isset($totalCounts['Updated']))
                        <h5 class="m-t-30">Incompelet Applications 
                            <span class="pull-right">{{ $updatedPercent = floor( ($totalCounts['Updated'] / $totalApplications) * 100 ) }}%</span>
                        </h5>
                        <div class="progress">
                            <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:{{$updatedPercent}}%; height:6px;"></div>
                        </div>
                    @endif
                    
                    @if (isset($totalCounts['Started']))
                        <h5 class="m-t-30">Initiated Applications 
                            <span class="pull-right">{{ $startedPercent = floor( ($totalCounts['Started'] / $totalApplications) * 100 ) }}%</span>
                        </h5>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:{{$startedPercent}}%; height:6px;"></div>
                        </div>
                    @endif
                @endif
            </div>
</div>
