<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div
        id="applicationActionsContainer"
        class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#applicationAction" aria-expanded="false" aria-controls="app_pInfo">
        <h5>{{__("Application Actions")}}</h5>
        <i class="mdi mdi-plus text-primary"></i>
        </div>
    </div>
    <div id="applicationAction" class="collapse" aria-labelledby="apph_pInfo" data-parent="#applicationActionsContainer">
        <div class="p-0 card-body row">
            @foreach (ApplicationHelpers::getActions() as $slug => $applicationAction)


                <div class="col-md-4 mt-2">
                <div
                    class="custom-helper"
                    :class="{ 'active' : applicationActions.includes('{{$slug}}') }"

                    @click="addApplicationAction('{{$slug}}')">

                        <span class="isActiveIcone" x-show="applicationActions.includes('{{$slug}}')">
                            <i class="fas fa-check-circle"></i>
                        </span>

                        <span class="custom-helper-icon">
                            <i class="{{$applicationAction['icon']}} mr-2 text-mute"></i>
                        </span>

                        <span class="custom-helper-title">
                            {{$applicationAction['title']}}
                        </span>
                </div>
                </div>




            @endforeach
        </div>
    </div>
</div>
