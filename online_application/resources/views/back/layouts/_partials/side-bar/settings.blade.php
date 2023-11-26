<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark has-arrow" href="javascript:void(0)" aria-expanded="false"
        data-toggle="tooltip" data-placement="right" title="{{__('Settings')}}">
        <i class="mdi mdi-settings"></i>
        <span class="hide-menu">{{__('Settings')}}</span>
    </a>

    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
            <a href="{{route('settings.index')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
                data-toggle="tooltip" data-placement="right" title="{{__('School Settings')}}">
                <i class="mdi mdi-school"></i>
                <span class="hide-menu">{{__('School Settings')}}</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{route('customfields.index')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
                data-toggle="tooltip" data-placement="right" title="{{__('Custom Fields')}}">
                <i class="mdi mdi-server"></i>
                <span class="hide-menu">{{__('Custom Fields')}}</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{route('users.index')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
                data-toggle="tooltip" data-placement="right" title="{{__('Users')}}">
                <i class="mdi mdi-account-settings-variant"></i>
                <span class="hide-menu">{{__('Users')}}</span>
            </a>
        </li>


        <li class="sidebar-item">
            <a href="{{route('roles.index')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
                data-toggle="tooltip" data-placement="right" title="{{__('User Roles')}}">
                <i class="mdi mdi-shield"></i>
                <span class="hide-menu">{{__('Roles')}}</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{route('api.key.show')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
                data-toggle="tooltip" data-placement="right" title="{{__('API Key')}}">
                <i class="mdi mdi-key"></i>
                <span class="hide-menu">{{__('API Key')}}</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{route('plugins.index')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
                data-toggle="tooltip" data-placement="right" title="{{__('Plugins & Integrations')}}">
                <i class="mdi mdi-puzzle"></i>
                <span class="hide-menu">{{__('Plugins & Integrations')}}</span>
            </a>
        </li>


        <li class="sidebar-item">
            <a href="{{route('import.upload')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
                data-toggle="tooltip" data-placement="right" title="{{__('Import')}}">
                <i class="mdi mdi-cloud-upload"></i>
                <span class="hide-menu">{{__('Import')}}</span>
            </a>
        </li>


        @include('back.layouts._partials.side-bar.application_status')
    </ul>
</li>
