<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark has-arrow"
       href="javascript:void(0)" aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('VAA')}}">
        <i class="mdi mdi-assistant"></i>
        <span class="hide-menu">{{__('VAA')}}</span>
        {{--  @features(['virtual_assistant'])
        @nofeatures(['virtual_assistant'])
        @include('back.layouts._partials.pro')
        @endfeatures
  --}}
        @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['virtual_assistant'])))

            @include('back.layouts._partials.pro')

        @endif
    </a>

    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
            <a href="{{route('assistantsBuilder.index')}}" class="sidebar-link waves-effect waves-dark"
               aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{__('VAA Builder')}}">
                <i class="mdi mdi-assistant"></i>
                <span class="hide-menu">{{__('VAA Builder')}}</span>
                @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['virtual_assistant'])))
                    @include('back.layouts._partials.pro')
                @endif
                {{--  @features(['virtual_assistant'])
                @nofeatures(['virtual_assistant'])
                    @include('back.layouts._partials.pro')
                @endfeatures  --}}
            </a>
        </li>
        @include('back.layouts._partials.side-bar.call-back')
    </ul>
</li>
