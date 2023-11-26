<!-- ============================================================== -->

<!-- Topbar header - style you can find in pages.scss -->

<!-- ============================================================== -->

<header class="topbar">

    <nav class="navbar top-navbar navbar-expand-md navbar-dark">

      <!--  <nav class="navbar top-navbar navbar-expand-md navbar-dark"> -->



        <div class="navbar-header">

            <!-- This is for the sidebar toggle which is visible on mobile only -->

            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>

            <!-- ============================================================== -->

            <!-- Logo -->

            <!-- ============================================================== -->

            <a class="navbar-brand" href="{{ route('school.home' ,  request()->tenant()->slug ) }}">


                    <img src="{{ asset('media/images/GBS-Main-Logo-White.png')}}" />

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

            <!-- ============================================================== -->

            <!-- Right side toggle and nav items -->

            <!-- ============================================================== -->

            <ul class="navbar-nav float-right">



                <!-- ============================================================== -->

                <!-- Comment -->

                <!-- ============================================================== -->

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell font-24"></i>



                    </a>

                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">

                        <span class="with-arrow"><span class="bg-primary"></span></span>

                        <ul class="list-style-none">

                            <li>

                                <div class="drop-title bg-primary text-white">

                                    <h4 class="m-b-0 m-t-5">1 New</h4>

                                    <span class="font-light">Notification</span>

                                </div>

                            </li>

                            <li>

                                <div class="message-center notifications">

                                    <!-- Message -->

                                    <a href="javascript:void(0)" class="message-item">

                                        <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>

                                        <span class="mail-contnet">

                                            <h5 class="message-title">GBSG Admin</h5> <span class="mail-desc">Just changed your application status to <strong>Approved</strong>!</span> <span class="time">9:30 AM</span> </span>

                                    </a>

                                    <!-- Message -->

                                </div>

                            </li>

                            <li>

                                <a class="nav-link text-center m-b-5" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>

                            </li>

                        </ul>

                    </div>

                </li>

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

                                    Message

                                    <a href="javascript:void(0)" class="message-item">

                                        <span class="user-img"> <img src="../../assets/images/users/1.jpg" alt="user" class="rounded-circle"> <span class="profile-status online pull-right"></span> </span>

                                        <span class="mail-contnet">

                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span> </span>

                                    </a>

                                    Message

                                    <a href="javascript:void(0)" class="message-item">

                                        <span class="user-img"> <img src="../../assets/images/users/2.jpg" alt="user" class="rounded-circle"> <span class="profile-status busy pull-right"></span> </span>

                                        <span class="mail-contnet">

                                            <h5 class="message-title">Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span> </span>

                                    </a>

                                    Message

                                    <a href="javascript:void(0)" class="message-item">

                                        <span class="user-img"> <img src="../../assets/images/users/3.jpg" alt="user" class="rounded-circle"> <span class="profile-status away pull-right"></span> </span>

                                        <span class="mail-contnet">

                                            <h5 class="message-title">Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span> </span>

                                    </a>

                                    Message

                                    <a href="javascript:void(0)" class="message-item">

                                        <span class="user-img"> <img src="../../assets/images/users/4.jpg" alt="user" class="rounded-circle"> <span class="profile-status offline pull-right"></span> </span>

                                        <span class="mail-contnet">

                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </span>

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



                <li class="nav-item">

                    <a class="nav-link" href="#"><i class="font-24 mdi mdi-help-circle"></i></a>

                </li>







                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../../assets/images/users/menu-icon.svg" alt="{{__('user')}}"  width="31"></a>

                    <div class="dropdown-menu dropdown-menu-right user-dd ">

                        <span class="with-arrow"><span class="bg-primary"></span></span>

                        <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">

                            <div class=""><img src="../../assets/images/users/1.jpg" alt="{{__('user')}}" class="img-circle" width="60"></div>

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



                        <a class="dropdown-item" href="#">{{__("Files")}}</a>



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
