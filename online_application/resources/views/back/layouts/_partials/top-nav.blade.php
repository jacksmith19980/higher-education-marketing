
<!-- ============================================================== -->

<!-- Topbar header - style you can find in pages.scss -->

<!-- ============================================================== -->

<header class="topbar">

    <nav class="navbar top-navbar navbar-expand-md navbar-light">

        <div class="navbar-header">

            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                <i class="ti-menu ti-close"></i>

                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->

                {{-- <a class="navbar-brand" href="{{ url('/dashboard') }}">--}}
                    {{-- @if (!isset($settings))--}}
                    {{-- @php--}}
                    {{-- $settings = [];--}}
                    {{-- @endphp--}}
                    {{-- @endif--}}
                    {{-- {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}--}}

                    {{-- </a>--}}

                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Toggle which is visible on mobile only -->
                <!-- ============================================================== -->

                <a class="navbar-brand d-block d-md-none" href="{{ url('/dashboard') }}">
                    @if(Route::currentRouteName() == 'home')
                        <img src="{{URL::asset('/assets/images/HEM_SP_logo_dark.svg')}}" alt="HEM SP logo" height="50" width="150"/>
                    @else
                        {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}
                    @endif
                </a>

                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                    data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more-alt"></i></a>

        </div>

        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->

        <div class="navbar-collapse collapse pl-md-4" id="navbarSupportedContent">

            <!-- Added Logo for desktop -->
            <a class="navbar-brand d-none d-md-block" href="{{ url('/dashboard')}}" style="max-width:260px;">
                @if(Route::currentRouteName() == 'home')
                    <img src="{{URL::asset('/assets/images/HEM_SP_logo_dark.svg')}}" alt="HEM SP logo" height="50" width="150"/>
                @else
                    {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}
                @endif
            </a>

            <!-- ============================================================== -->
            <!-- top nav menu items -->
            <!-- ============================================================== -->
            @if(Route::currentRouteName() != 'home')
            @include('back.layouts._partials.top-nav-menu.top-nav-menu-items')
            @else
            <ul class="navbar-nav float-left mr-auto d-none d-md-inline-flex">
            </ul>
            @endif
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="float-right navbar-nav">
                @if(session('tenant') && strpos(Route::currentRouteName(), "users.") === false  )
                @include('back.layouts._partials.messages' , [
                    'newMessages' => DirectMessage::getNewMessages(Auth::guard('web')->user())
                ])
                @endif

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                            src="../../assets/images/users/menu-icon.svg" alt="{{__('user')}}"
                            width="31"></a>

                    <div class="dropdown-menu dropdown-menu-right user-dd">

                        <span class="with-arrow"><span class="bg-primary"></span></span>

                        <div class="text-white d-flex no-block align-items-center p-15 bg-primary m-b-10">

                            <div class=""><img src="../../assets/images/users/1.jpg" alt="{{__('user')}}"
                                    class="img-circle" width="60"></div>

                            <div class="m-l-10">

                                <h4 class="m-b-0">{{auth()->user()->name}}</h4>

                                <p class=" m-b-0">{{auth()->user()->email}}</p>

                            </div>

                        </div>

                        <a class="dropdown-item" href="{{route('user.profile')}}"><i class="ti-user m-r-5 m-l-5"></i> {{__('My
                            Profile')}}</a>





                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault();
                                                                                                            document.getElementById('logout-form').submit();">

                            <i class="fa fa-power-off m-r-5 m-l-5"></i>{{ __('Logout') }}

                        </a>



                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

                            @csrf

                        </form>

						@features(['sis'])
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('settings.index')}}#itour">
                            {{__('Settings tour')}}
                        </a>

                        <a class="dropdown-item"
                            href="{{route('applications.index', ['hash' => 'newapplication'])}}#newapplication">
                            {{__('Application tour')}}
                        </a>

                        <a class="dropdown-item" href="{{route('campuses.index')}}#createcampus">
                            {{__('Campus tour')}}
                        </a>

                        <a class="dropdown-item" href="{{route('programs.index')}}#createprogram">
                            {{__('Programs tour')}}
                        </a>

                        <a class="dropdown-item" href="{{route('courses.index')}}#createcourse">
                            {{__('Course tour')}}
                        </a>
						@endfeatures

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('schools.index')}}">{{__("All Schools")}}</a>

                        @if(auth()->guard('web')->user()->isHem)
                            <a class="dropdown-item" href="{{route('schools.create')}}">{{__("Add New School")}}</a>
                        @endif

                        @if (!Auth::guest() && Auth::user()->roles == 'Super Admin')
                        <a class="dropdown-item" href="{{route('plans.index')}}">{{__("Plans")}}</a>
                        @endif

                        <div class="dropdown-divider"></div>


                        @if($schools->count())

                        @foreach ($schools as $school)

                        <a class="dropdown-item" href="{{route('tenant.switch' , $school)}}">{{ $school->name }}</a>

                        @endforeach

                        @endif







                        <!-- <div class="dropdown-divider"></div>

                        <div class="p-10 p-l-30"><a href="javascript:void(0)" class="btn btn-sm btn-success btn-rounded">View Profile</a></div> -->

                    </div>

                </li>

                <!-- ============================================================== -->

                <!-- User profile and search -->

                <!-- ============================================================== -->

            </ul>

        </div>

    </nav>

</header>

<!-- ============================================================== -->

<!-- End Topbar header -->

<!-- ============================================================== -->
