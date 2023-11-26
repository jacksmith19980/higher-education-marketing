<li class="sidebar-item">
    <a href="{{route('envelope.index')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false"
        data-toggle="tooltip" data-placement="right" title="{{__('E-Signatures')}}">
        <i class="mdi mdi-lead-pencil"></i>
        <span class="hide-menu">{{__('E-Signatures')}}</span>

        @if(isset($settings['plan']['features']) && empty(array_intersect($settings['plan']['features'],['e-signature'])))

            @include('back.layouts._partials.pro')

        @endif



    </a>
</li>
