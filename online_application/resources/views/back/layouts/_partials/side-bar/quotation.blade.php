<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark has-arrow" href="javascript:void(0)" aria-expanded="false"
        data-toggle="tooltip" data-placement="right" title="{{__('Quote Builder')}}">
        <i class="mdi mdi-coin"></i>
        <span class="hide-menu">{{__('Quote Builder')}}</span>
        {{--  @features(['quote_builder'])
        @nofeatures(['quote_builder'])
            @include('back.layouts._partials.pro')
        @endfeatures
  --}}
        @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['quote_builder'])))
            @include('back.layouts._partials.pro')
        @endif
    </a>

    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
            <a href="{{route('quotations.index')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
                data-toggle="tooltip" data-placement="right" title="{{__('Build Quote Builder')}}">
                <i class="mdi mdi-coin"></i>
                <span class="hide-menu">{{__('Build Quote Builder')}}</span>

            {{--      @features(['quote_builder'])
                @nofeatures(['quote_builder'])
                @include('back.layouts._partials.pro')
                @endfeatures
  --}}
                @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['quote_builder'])))
                    @include('back.layouts._partials.pro')
                @endif

            </a>
        </li>
        @include('back.layouts._partials.side-bar.promocodes')
    </ul>
</li>
