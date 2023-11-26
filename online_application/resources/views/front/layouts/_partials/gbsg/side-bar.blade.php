<!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('dashboard')}}" aria-expanded="false">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span class="hide-menu">{{__('Dashboard')}}</span>
                            </a>
                        </li>
                        @tenant
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('agencies.index')}}" aria-expanded="false">
                                <i class="mdi mdi-school"></i>
                                <span class="hide-menu">{{__('Students')}}</span>
                            </a>
                        </li>


                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('applications.index')}}" aria-expanded="false">
                                <i class="mdi mdi-reorder-horizontal"></i>
                                <span class="hide-menu">{{__('Applications')}}</span>
                            </a>
                        </li>


                        <li class="sidebar-item">

                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-bank"></i>
                                <span class="hide-menu">{{__('Agencies')}}</span>
                            </a>

                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{route('agencies.index')}}" class="sidebar-link">
                                        <i class="mdi mdi-bank"></i>
                                        <span class="hide-menu">{{__('Agencies')}}</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="component-nestable.html" class="sidebar-link">
                                        <i class="mdi mdi-account-multiple"></i>
                                        <span class="hide-menu">{{__('Agents')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endtenant

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->