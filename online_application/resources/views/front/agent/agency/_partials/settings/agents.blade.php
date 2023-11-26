<div class="tab-pane fade" id="v-pills-add-agents" role="tabpanel" aria-labelledby="v-pills-add-agents-tab">
    <h4 class="card-title">{{__('Agents')}}</h4>
    <form method="POST" action="{{route('school.agent.agency.update' , ['school'=>$school , 'agency' => $agency])}}" class="m-b-40" validate>
        
        @csrf
        <input type="hidden" name="action" value="invite_agents">
        @include('front.agent.agency._partials.add-agents')
        
    <input type="submit" class="btn btn-success float-right" value="{{__('Invite')}}">
    </form>
    
    @if($agents->count())
        @include('front.agent.agency._partials.agents-list')
    @endif
</div>