<div class="tab-pane fade show active" id="v-pills-general-info" role="tabpanel" aria-labelledby="v-pills-general-info-tab">
   
    <h4 class="card-title mb-2">{{__('General Information')}}</h4>
    <form method="POST" action="{{route('school.agent.agency.update' , ['school'=>$school , 'agency' => $agency])}}" class="" validate>
        @csrf
        @include('front.agent.agency._partials.agency-info')
        <input type="submit" class="btn btn-success float-right" value="Update">
    </form>

    </div>