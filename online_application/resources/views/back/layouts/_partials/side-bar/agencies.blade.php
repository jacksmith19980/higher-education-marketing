<li class="sidebar-item">

    @if( isset($settings['agencies']['load_mautic_agencies']) && $settings['agencies']['load_mautic_agencies'] == 'Yes' )

        <a class="sidebar-link waves-effect waves-dark sidebar-link has-arrow"
            href="javascript:void(0)" aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('Recruiters Hub')}}">
            <i class="mdi mdi-store-24-hour"></i>
            <span class="hide-menu">{{__('Recruiters Hub')}}</span>
            {{--  @features(['agency'])
            @nofeatures(['agency'])
            @include('back.layouts._partials.pro')
            @endfeatures
  --}}
            @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['agency'])))

                @include('back.layouts._partials.pro')

            @endif
        </a>

        <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
                <a href="{{ route('agencies.index') }}" class="sidebar-link waves-effect waves-dark sidebar-link"
                   aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('Recruiters Hub')}}">
                    <i class="mdi mdi-store-24-hour"></i>
                    <span class="hide-menu">{{__('Recruiters Hub')}}</span>
                    @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['agency'])))

                        @include('back.layouts._partials.pro')

                    @endif
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('agencies.index', ['source'=> 'mautic']) }}" class="sidebar-link waves-effect waves-dark sidebar-link"
                   aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('Mautic Recruiters Hub')}}">
                    <i class="mdi mdi-store-24-hour"></i>
                    <span class="hide-menu">{{__('Mautic Recruiters Hub')}}</span>
                    @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['agency'])))

                        @include('back.layouts._partials.pro')

                    @endif
                </a>
            </li>
        </ul>

    @else

        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('agencies.index') }}" aria-expanded="false"
           data-toggle="tooltip" data-placement="right" title="{{__('Recruiters Hub')}}">
                <i class="mdi mdi-store-24-hour"></i>
                <span class="hide-menu">{{__('Recruiters Hub')}}</span>
                @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['agency'])))

                        @include('back.layouts._partials.pro')

                    @endif
        </a>
    @endif
</li>
