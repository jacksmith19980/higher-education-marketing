<form method="POST" name="plugin-auth-form" action="{{ route('plugins.store' , ['plugin' => $pluginName]) }}"
    enctype="multipart/form-data">

    @csrf

    <ul class="nav nav-tabs" role="tablist">

        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#auth" role="tab">
                <span class="hidden-xs-down">{{__('General')}}</span>
            </a>
        </li>

       {{--   <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#stages" role="tab">
                <span class="hidden-xs-down">{{__('Stages')}}</span>
            </a>
        </li>  --}}

        {{--  <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                <span class="hidden-xs-down">{{__('Settings')}}</span>
            </a>
        </li>  --}}
    </ul>
    <div class="tab-content tabcontent-border">
        <div class="tab-pane p-20 active" id="auth" role="tabpanel">
            @include('back.plugins.campuslogin.auth')
        </div>
       {{--   <div class="tab-pane p-20" id="stages" role="tabpanel">
            @include('back.plugins.hubspot.stages')
        </div>
        <div class="tab-pane p-20" id="settings" role="tabpanel">
            @include('back.plugins.hubspot.settings')
        </div>  --}}

    </div>

</form>
