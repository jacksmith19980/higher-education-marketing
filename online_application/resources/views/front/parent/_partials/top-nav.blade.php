<!-- ============================================================== -->

<!-- Topbar header - style you can find in pages.scss -->

<!-- ============================================================== -->

<header class="topbar">

    <nav class="navbar top-navbar navbar-expand-md navbar-light">

        <div class="navbar-header">

            <!-- This is for the sidebar toggle which is visible on mobile only -->

            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>

            <!-- ============================================================== -->

            <!-- Logo -->

            <!-- ============================================================== -->

            <a class="navbar-brand" href="{{ url('/dashboard') }}">
                    @if (!isset($settings))
                        @php
                            $settings = [];
                        @endphp
                    @endif
                    {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}

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



               <!-- <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li> -->





                <!-- ============================================================== -->

                <!-- Search -->

                <!-- ============================================================== -->

                <li class="nav-item search-box">



                    <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>

                    <form class="app-search position-absolute">

                        <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>

                    </form>

                </li>

            </ul>

            <!-- ============================================================== -->

            <!-- Right side toggle and nav items -->

            <!-- ============================================================== -->

            <ul class="navbar-nav float-right">

                <!-- ============================================================== -->

                <!-- create new -->

                <!-- ============================================================== -->

              <!--   <li class="nav-item dropdown">

                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <i class="flag-icon flag-icon-us"></i>

                  </a>

                  <div class="dropdown-menu dropdown-menu-right  animated bounceInDown" aria-labelledby="navbarDropdown">

                      <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-us"></i> English</a>

                      <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i> French</a>

                      <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-es"></i> Spanish</a>

                      <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-de"></i> German</a>

                  </div>

              </li> -->

                <!-- ============================================================== -->

                <!-- Comment -->

                <!-- ============================================================== -->

                @tenant

                    <!-- <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="mdi mdi-bell font-24"></i>

                        </a>

                               {{-- @include('back.layouts._partials.notifications') --}}



                    </li> -->

                @endtenant

                <!-- ============================================================== -->

                <!-- End Comment -->

                <!-- ============================================================== -->

                <!-- ============================================================== -->

                <!-- Messages -->

                <!-- ============================================================== -->

                <!-- <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="font-24 mdi mdi-comment-processing"></i>



                    </a>

                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">

                        <span class="with-arrow"><span class="bg-danger"></span></span>

                        <ul class="list-style-none">

                            <li>

                                <div class="drop-title text-white bg-danger">

                                    <h4 class="m-b-0 m-t-5">5 New</h4>

                                    <span class="font-light">Messages</span>

                                </div>

                            </li>

                            <li>

                                <div class="message-center message-body">


                                    <a href="javascript:void(0)" class="message-item">

                                        <span class="user-img"> <img src="../../assets/images/users/1.jpg" alt="user" class="rounded-circle"> <span class="profile-status online pull-right"></span> </span>

                                        <span class="mail-contnet">

                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span> </span>

                                    </a>



                                </div>

                            </li>

                            <li>

                                <a class="nav-link text-center link" href="javascript:void(0);"> <b>See all e-Mails</b> <i class="fa fa-angle-right"></i> </a>

                            </li>

                        </ul>

                    </div>

                </li> -->

                <!-- ============================================================== -->

                <!-- End Messages -->

                <!-- ============================================================== -->

                <!-- ============================================================== -->

                <!-- User profile and search -->

                <!-- ============================================================== -->

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../../assets/images/users/menu-icon.svg" alt="{{__('user')}}" width="31"></a>

                    <div class="dropdown-menu dropdown-menu-right user-dd">

                        <span class="with-arrow"><span class="bg-primary"></span></span>

                        <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">

                            <div class=""><img src="../../assets/images/users/1.jpg" alt="{{__('user')}}" class="img-circle" width="60"></div>

                            <div class="m-l-10">

                                <h4 class="m-b-0">{{auth()->guard('student')->user()->name}}</h4>

                                <p class=" m-b-0">{{auth()->guard('student')->user()->email}}</p>

                            </div>

                        </div>



                        <a class="dropdown-item" href="{{route('school.parents' , $school)}}"><i class="ti-user m-r-5 m-l-5"></i> My Account</a>



                        <div class="dropdown-divider"></div>

                        <!-- <a class="dropdown-item" href="{{route('schools.index')}}">{{__("Applications")}}</a>

                        <a class="dropdown-item" href="{{route('schools.index')}}">{{__("Students")}}</a> -->

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

                        <div class="p-l-30 p-10"><a href="javascript:void(0)" class="btn btn-sm btn-success btn-rounded">View Profile</a></div> -->

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
