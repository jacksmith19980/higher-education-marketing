<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark has-arrow"
       href="javascript:void(0)" aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('SIS')}}">
        <i class="mdi mdi-school"></i>
        <span class="hide-menu">{{__('SIS')}}</span>
        {{--  @features(['sis'])
        @nofeatures(['sis'])
        @include('back.layouts._partials.pro')
        @endfeatures  --}}

        @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['sis'])))
            @include('back.layouts._partials.pro')
        @endif
    </a>
    {{-- @features(['sis']) --}}
    <ul aria-expanded="false" class="collapse first-level">
        @include('back.enrolled.side-bar')
        @include('back.instructors.side-bar')
        @include('back.classrooms.side-bar')
        @include('back.lessons.side-bar')
        @include('back.attendances.side-bar')
        @include('back.groups.side-bar')
        @include('back.semesters.side-bar')
        @include('back.calendar.side-bar')
    </ul>
    {{-- @endfeatures --}}


</li>
