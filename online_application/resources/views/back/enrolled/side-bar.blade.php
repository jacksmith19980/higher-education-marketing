<li class="sidebar-item">
    @if (isset($settings['auth']['parent_login']) && $settings['auth']['parent_login'] == 'Yes')
        <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
                <a href="{{ route('students.enrolled.index', ['type' => 'student']) }}" class="sidebar-link waves-effect waves-dark"
                    data-toggle="tooltip" data-placement="right" title="{{ __('Students') }}">
                    <i class="mdi mdi-hexagon-multiple"></i>
                    <span class="hide-menu">{{ __('Students') }}</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a aria-expanded="false" href="{{ route('students.index', ['type' => 'parent']) }}"
                    class="sidebar-link waves-effect waves-dark" data-toggle="tooltip" data-placement="right"
                    title="{{ __('Parents') }}">
                    <i class="fas fa-male"></i>
                    <span class="hide-menu">{{ __('Parents') }}</span>
                </a>
            </li>
        </ul>
    @else
        <a href="{{ route('students.enrolled.index') }}" class="sidebar-link waves-effect waves-dark sidebar-link"
            data-toggle="tooltip" data-placement="right" title="{{ __('Students') }}">
            <i class="mdi mdi-hexagon-multiple"></i>
            <span class="hide-menu">{{ __('Students') }}</span>
            @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['sis'])))
            @include('back.layouts._partials.pro')
        @endif
        </a>
    @endif
</li>
