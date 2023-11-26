<li class="sidebar-item">
    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
        <i class="mdi mdi-school"></i>
        <span class="hide-menu">{{__('School')}}</span>
    </a>

    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
            <a href="{{route('campuses.index')}}" class="sidebar-link">
                <i class="mdi mdi-crosshairs-gps"></i>
                <span class="hide-menu">{{__('Campuses')}}</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{route('courses.index')}}" class="sidebar-link">
                <i class="mdi mdi-hexagon-multiple"></i>
                <span class="hide-menu">{{__('Courses')}}</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{route('programs.index')}}" class="sidebar-link">
                <i class="mdi mdi-hexagon-multiple"></i>
                <span class="hide-menu">{{__('Programs')}}</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{route('settings.index')}}" class="sidebar-link">
                <i class="mdi mdi-settings"></i>
                <span data-name="settings_tab" class="hide-menu">{{__('Settings')}}</span>
            </a>
        </li>

    </ul>

</li>