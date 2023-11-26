<li class="sidebar-item">
    <a href="{{route('attendances.back.index')}}" class="sidebar-link waves-effect waves-dark sidebar-link"
       aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('Attendance')}}">
        <i class="mdi mdi-hexagon-multiple"></i>
        <span class="hide-menu">{{__('Attendance')}}</span>
        {{--  @features(['sis'])
        @nofeatures(['sis'])
            @include('back.layouts._partials.pro')
        @endfeatures  --}}

        @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['sis'])))
            @include('back.layouts._partials.pro')
        @endif
    </a>
</li>
