<!-- ============================================================== -->

<!-- Topbar header - style you can find in pages.scss -->

<!-- ============================================================== -->
<header class="topbar"  style="
                        {{isset($settings['auth']['background_color'])? 'background-color: '.$settings['auth']['background_color'].'' : ''}}
                        ">

    <nav class="navbar top-navbar navbar-expand-md navbar-light nav_{{$school->slug}}">

      <!--  <nav class="navbar top-navbar navbar-expand-md navbar-dark"> -->



        <div class="navbar-header" style="margin-left: 50%; transform: translateX(-50%);">

            <!-- This is for the sidebar toggle which is visible on mobile only -->

            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>

            <!-- ============================================================== -->

            <!-- Logo -->

            <!-- ============================================================== -->

            <a class="navbar-brand" href="{{ route('school.home' ,  request()->tenant()->slug ) }}">
                @php $locale = Config::get('app.locale') @endphp
                @if($locale)
                    @if(isset($settings['branding']['logos'][$locale]['path']))
                        <span class="school-logo">
                            <img src="{{ Storage::disk('s3')->temporaryUrl($settings['branding']['logos'][$locale]['path'], \Carbon\Carbon::now()->addMinutes(5)) }}" style="max-height:60px;" />
                        </span>
                            @endif
                     @else
                    <h3>{{$school->name}}</h3>

                @endif

                <!-- not in use any more -->
                   @if(isset($settings['branding']['logo']))

                    <!-- <img src="{{ Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5)) }}" style="max-height:60px;" /> -->

                   @else
                        <h3>{{$school->name}}</h3>
                   @endif
                <!-- not in use any more -->
            </a>

            <!-- ============================================================== -->

            <!-- End Logo -->

            <!-- ============================================================== -->

            <!-- ============================================================== -->

            <!-- Toggle which is visible on mobile only -->

            <!-- ============================================================== -->

            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>

        </div>

        <!-- ============================================================== -->

        <!-- End Logo -->

        <!-- ============================================================== -->

        <div class="navbar-collapse collapse" id="navbarSupportedContent">

            <!-- ============================================================== -->

            <!-- toggle and nav items -->

            <!-- ============================================================== -->

            <ul class="navbar-nav float-left mr-auto">



            </ul>

            @impersonate

                @impersonateStudent

                    @include('front.layouts._partials.rounded.stop-impersonate-student')

                @endimpersonateStudent


                @impersonateChild

                    @include('front.layouts._partials.rounded.stop-impersonate-child')

                @endimpersonateStudent


            @else

            <!-- ============================================================== -->

            <!-- Right side toggle and nav items -->

            <!-- ============================================================== -->

            <ul class="navbar-nav float-right">

                <li class="nav-item dropdown">
                    @if($student->avatar)
                        @php
                            $storageUrl = env('AWS_URL').$student->avatar;
                        @endphp
                    @endif

                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('media/images/users/menu-icon.svg') }}" alt="{{__('user')}}" width="31"></a>

                    <div class="dropdown-menu dropdown-menu-right user-dd">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
                            <div class=""><img src="{{$student->avatar ? $storageUrl : asset('media/images/blankavatar.png')}}" alt="{{__('user')}}" class="img-circle" width="60"></div>
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



                         <a class="dropdown-item" href="#"

                                       onclick="event.preventDefault();

                                                     document.getElementById('logout-form').submit();">

                                       <i class="fa fa-power-off m-r-5 m-l-5"></i>{{ __('Logout') }}

                                    </a>



                        <form id="logout-form" action="{{ route('school.logout' , $school) }}" method="POST" style="display: none;">

                            @csrf

                        </form>



                        <!-- <div class="dropdown-divider"></div>

                        <div class="p-l-30 p-10"><a href="javascript:void(0)" class="btn btn-sm btn-success btn-rounded">{{__('View Profile')}}</a></div> -->

                    </div>

                </li>





                <!-- ============================================================== -->

                <!-- User profile and search -->

                <!-- ============================================================== -->

            </ul>
        @endimpersonate
        </div>

    </nav>

</header>

<!-- ============================================================== -->

<!-- End Topbar header -->

<!-- ============================================================== -->
