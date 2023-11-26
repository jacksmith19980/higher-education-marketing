<li class="sidebar-item">
    <a href="{{route('followups.index' , ['type' => 'call'])}}" class="sidebar-link waves-effect waves-dark sidebar-link"
       aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('Call Back Requests')}}">
        <i class="mdi mdi-cellphone-basic"></i>
        <span class="hide-menu">{{__('Call Back Requests')}}</span>
        {{--  @features(['quote_builder'])
        @nofeatures(['quote_builder'])
            @include('back.layouts._partials.pro')
        @endfeatures  --}}

        @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['quote_builder'])))
            @include('back.layouts._partials.pro')
        @endif
    </a>
</li>
