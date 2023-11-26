<header class="topbar" style="{{isset($settings['auth']['background_color'])? 'background-color: '.$settings['auth']['background_color'].'' : ''}}">
    <nav class="navbar top-navbar navbar-expand-md navbar-light nav_{{$school->slug}}">
        <div class="navbar-collapse collapse pl-md-4 payment-navbar" id="navbarSupportedContent">
            <a class="navbar-brand d-none d-md-block front-logo" style="width:120px;" href="{{ url('/') }}">
                @if (!isset($settings))
                    @php
                        $settings = [];
                    @endphp
                @endif
                {!! SchoolHelper::renderSchoolLogo(optional(request()->tenant()), $settings) !!}
            </a>

            <ul class="navbar-nav float-left mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('application.index', $school)}}" aria-expanded="false">
                        <span class="d-none d-md-block">{{__('Dashboard')}}</span>
                    </a>
                </li>
            </ul>

            @impersonate
                @impersonateStudent
                    @include('front.layouts._partials.oiart.stop-impersonate-student')
                @endimpersonateStudent
                @impersonateChild
                    @include('front.layouts._partials.oiart.stop-impersonate-child')
                @endimpersonateStudent
            @else
                @if($student)
                    <ul class="navbar-nav float-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('media/images/users/menu-icon.svg') }}{{--$student->avatar--}}" alt="{{__('user')}}"  width="31"></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd">
                                <span class="with-arrow"><span class="bg-primary"></span></span>
                                <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
                                    <div class="">
                                        @if ($student->avatar)
                                            @php
                                                $storageUrl = env('AWS_URL').$student->avatar;
                                            @endphp
                                            <img src="{{$storageUrl}}" alt="user" class="rounded-circle" width="31" style="width:35px; max-height:35px">
                                        @else
                                            <img src="{{ asset('media/images/blankavatar.png') }}" alt="{{__('user')}}" class="rounded-circle" style="width: 35px; max-height:35px">
                                        @endif
                                        <img src="{{--$student->avatar--}}" alt="user" class="img-circle" width="60" style="display: none;">
                                    </div>

                                    <div class="m-l-10">
                                        <h4 class="m-b-0">{{$student->name}}</h4>
                                        <p class=" m-b-0">{{$student->email}}</p>
                                    </div>
                                </div>

                                <a class="dropdown-item" href="{{route('student.profile', $school)}}">
                                    <i class="icon-user m-r-5 m-l-5"></i>
                                    {{__('My Profile')}}
                                </a>

                                <a class="dropdown-item" href="{{route('application.index' , $school)}}">
                                    <i class="icon-docs m-r-5 m-l-5"></i>
                                    {{__("My Applications")}}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off m-r-5 m-l-5"></i>{{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('school.logout' , $school) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                @endif
            @endimpersonate
        </div>
    </nav>
</header>
