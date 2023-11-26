<div class="col-md-6 offset-md-3">
    <div class="card">
        <div class="card-body">
            <div class="">

                <div>
                    <h4 class="card-title">{{__('Agency Information')}}</h4>
                </div>

                <div class="ml-auto d-flex no-block align-items-center">

                    <form method="POST"
                          action="{{route('school.agent.agency.update' , ['school'=>$school , 'agency' => $agency])}}"
                          class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update Agency') }}"
                          data-add-button="{{__('Update')}}">

                        @csrf

                        @if($errors->has('invalid'))
                            <div class="alert alert-danger">
                                {{$errors->first('invalid')}}
                            </div>
                    @endif
                       
                    <!-- Step 1 -->
                    @include('front.agent.agency._partials.agency-info')
                    
                    
                    
                    <!-- Step 2 -->
                    @include('front.agent.agency._partials.add-agents')
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>