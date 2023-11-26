<li class="sidebar-item">
    <a href="{{route('grades.index')}}" class="sidebar-link waves-effect waves-dark sidebar-link"
       aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('Grades')}}">
        <i class="mdi mdi-hexagon-multiple"></i>
        <span class="hide-menu">{{__('Grades')}}</span>
        @features(['sis'])
        @nofeatures(['sis'])
            @include('back.layouts._partials.pro')
        @endfeatures
    </a>
</li>
