<header class="topbar" style="{{isset($settings['auth']['background_color'])? 'background-color: '.$settings['auth']['background_color'].'' : ''}}">

    <nav class="navbar top-navbar navbar-expand-md navbar-light nav_{{$school->slug}}">

        <div class="navbar-header">

            <!-- This is for the sidebar toggle which is visible on mobile only -->

            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>

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


        <div class="navbar-collapse collapse pl-md-4" id="navbarSupportedContent">


            <ul class="float-left mr-auto navbar-nav"></ul>

            <!-- ============================================================== -->

            <!-- Right side toggle and nav items -->

            <!-- ============================================================== -->

            <ul class="float-right navbar-nav">


                <button type="button" class="btn btn-success"
                        onclick="app.addStudent('{{route('school.agent.student.create' , $school)}}' , ' ' , 'Create Student\'s Account' , this )">
                    <i class="fa fa-plus"></i>{{__('Add Student')}}
                </button>




                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{auth()->guard('agent')->user()->avatar}}" alt="{{__('user')}}" width="31"></a>

                    <div class="dropdown-menu dropdown-menu-right user-dd">

                        <span class="with-arrow"><span class="bg-info"></span></span>

                        <div class="text-white d-flex no-block align-items-center p-15 bg-info m-b-10">

                            <div class=""><img src="{{auth()->guard('agent')->user()->avatar}}" alt="user" class="img-circle" width="60"></div>

                            <div class="m-l-10">

                                <h4 class="m-b-0">{{auth()->guard('agent')->user()->name}}</h4>

                                <p class=" m-b-0">{{auth()->guard('agent')->user()->email}}</p>

                            </div>

                        </div>
                        <a class="dropdown-item" href="{{route('school.agent.home' , [$school])}}">
                            <i class="icon-people m-r-5 m-l-5"></i> {{__('Applicants')}}
                        </a>
                        <div class="dropdown-divider" style="border-bottom:1px solid #eef5f9"></div>

                        @agentAdmin
                            <a class="dropdown-item" href="{{route('school.agent.agency.edit' , [ 'school'=>$school , 'agency' => $agency ])}}"><i class="icon-settings m-r-5 m-l-5"></i>{{__("Settings")}}</a>
                        @endagentAdmin


                        <a class="dropdown-item" href="{{route('school.agent.edit' , [$school])}}">
                            <i class="ti-user m-r-5 m-l-5"></i> {{__('My Account')}}
                        </a>

                        <div class="dropdown-divider" style="border-bottom:1px solid #eef5f9"></div>

                         <a class="dropdown-item" href="#"

                                       onclick="event.preventDefault();

                                                     document.getElementById('logout-form').submit();">

                                       <i class="fa fa-power-off m-r-5 m-l-5"></i>{{ __('Logout') }}

                                    </a>



                        <form id="logout-form" action="{{ route('school.agent.logout' , $school) }}" method="POST" style="display: none;">

                            @csrf

                        </form>

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
