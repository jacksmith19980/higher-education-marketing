<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="javascript:void(0)">
                @if (!isset($settings))
                    @php
                        $settings = [];
                    @endphp
                @endif
                {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}
            </a>
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav float-left mr-auto">
                <li class="nav-item">
                    <a class="btn btn-light nav-link" href="{{route('school.instructor.home', $school)}}" aria-expanded="false">
                        <span class="d-none d-md-block">{{__('Dashboard')}}</span>
                    </a>
                </li>
            </ul>

            @if(session('impersonate-instructor') !== null && !empty(session('impersonate-instructor')))
            <form id="stop-impersonating-form" action="{{ route('instructors.stop.impersonate' , ['instructor' => session('impersonate-instructor')] ) }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a
                class="btn btn-danger mr-3" href="#"
                onclick="event.preventDefault();
                document.getElementById('stop-impersonating-form').submit();">
                {{__('Stop Impersonating')}}
            </a>
            @endif
            <ul class="navbar-nav float-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{auth()->guard('instructor')->user()->avatar}}" alt="{{__('user')}}" width="31"></a>
                    <div class="dropdown-menu dropdown-menu-right user-dd">
                        <span class="with-arrow"><span class="bg-info"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-info text-white m-b-10">
                            <div class=""><img src="{{auth()->guard('instructor')->user()->avatar}}" alt="{{__('user')}}" class="img-circle" width="60"></div>
                            <div class="m-l-10">
                                <h4 class="m-b-0">{{auth()->guard('instructor')->user()->name}}</h4>
                                <p class=" m-b-0">{{auth()->guard('instructor')->user()->email}}</p>
                            </div>
                        </div>
                        <div class="dropdown-divider" style="border-bottom:1px solid #eef5f9"></div>
                        <a class="dropdown-item" href="{{route('instructor.profile', $school)}}">
                            <i class="ti-user m-r-5 m-l-5"></i> {{__('My Account')}}
                        </a>
                        <div class="dropdown-divider" style="border-bottom:1px solid #eef5f9"></div>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault();

                                            document.getElementById('logout-form').submit();">

                            <i class="fa fa-power-off m-r-5 m-l-5"></i>{{ __('Logout') }}

                        </a>
                        <form id="logout-form" action="{{ route('school.instructor.logout' , $school) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
