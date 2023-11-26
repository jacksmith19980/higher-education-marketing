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

            <a class="navbar-brand" href="javascript:void(0)">
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

            <ul class="navbar-nav float-left mr-auto"></ul>

            <!-- ============================================================== -->

            <!-- Right side toggle and nav items -->

            <!-- ============================================================== -->

            <ul class="navbar-nav float-right">

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
                
                                        <span class="user-img"> <img src="{{asset('media/images/users/1.jpg')}}" alt="user" class="rounded-circle"> <span class="profile-status online pull-right"></span> </span>
                
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