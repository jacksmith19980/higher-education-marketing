<li class="sidebar-item">
    <a href="{{route('calendar.index')}}" class="sidebar-link waves-effect waves-dark sidebar-link"
       aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('Calendar')}}">
        <i class="mdi mdi-hexagon-multiple"></i>
        <span class="hide-menu">{{__('Calendar')}}</span>
        {{--  @features(['sis'])
        @nofeatures(['sis'])
            @include('back.layouts._partials.pro')
        @endfeatures  --}}
        @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['sis'])))
            @include('back.layouts._partials.pro')
        @endif

    </a>
</li>
