<div class="col-12">
    <div class="card" style="padding-right: 20px;">
        <div class="card-body">

            <div class="float-left">
                <h4 class="page-title">{{$title}} <span class="hide float-right" id="archived-text">&nbsp; / Archived</span></h4>
            </div>

            @if(Route::is('submissions.index') )
            <div class="float-right">
                <div>Group by applicant</div>
                <div class="vertical-center">
                    <span>No</span>
                    <label class="custom-switch">
                        <input id="groupbyemail" type="checkbox">
                        <span class="slider cround"></span>
                    </label>
                    <span>Yes</span>
                </div>
            </div>
            @endif

            @if($show_buttons)
                <div class="float-right btn-group" role="group">


                    @if(isset($show_login_button) && $show_login_button)
                        <a href="{{route('school.agent.login', $school)}}" style="margin-right: 10%; background-color: #004d6e" class="btn btn-block text-light" target="_blank">{{__('Agents Login')}}<i class="mdi mdi-menu-right" style="font-size: 16px"></i></a>
                    @endif

                    <a href="{{route( $params['modelName'].'.create')}}" class="btn btn-secondary add_new_btn text-light">{{__('Add New')}}</a>

                </div>

            @elseif(isset($specialButton))
            <div class="float-right btn-group" role="group">
                <a href="{{$specialButton['link']}}" {{$specialButton['attr']}} class="btn btn-secondary add_new_btn text-light">{{__($specialButton['button_label'])}}</a>
            </div>

            @elseif(isset($modal))
                <div class="float-right btn-group" role="group">
                    <a href="javascript:void(0)" onclick="{{$modal}}"  class="btn btn-secondary add_new_btn text-light">
                        {{__($button_label)}}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
