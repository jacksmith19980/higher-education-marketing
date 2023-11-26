<li class="sidebar-item">
    @if (!(isset($settings['auth']['parent_login']) && $settings['auth']['parent_login'] == 'Yes'))
        <a class="sidebar-link waves-effect waves-dark " href="{{ route('students.leads') }}" aria-expanded="false"
            data-toggle="tooltip" data-placement="right" title="{{ __('Leads') }}">
            <i class="mdi mdi-school"></i>
            <span class="hide-menu">{{ __('Leads') }}</span>


            @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['virtual_assistant'])))
                    @include('back.layouts._partials.pro')
                @endif

        </a>
    @endif
</li>
