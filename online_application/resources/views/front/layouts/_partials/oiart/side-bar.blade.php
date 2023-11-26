<aside class="left-sidebar">

    <!-- Added slide button for desktop and mobile -->
    <a class="d-block nav-link sidebartoggler waves-effect waves-light sidebar-nav-toggler" href="javascript:void(0)"><i
                class="ti-arrow-left"></i></a>

    <a class="nav-toggler waves-effect waves-light d-block sidebar-nav-toggler" href="javascript:void(0)"><i
                class="ti-arrow-left"></i></a>


    <div class="scroll-sidebar">
        <nav class="sidebar-nav">

            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark" href="{{route('application.index', $school)}}" aria-expanded="false">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span class="hide-menu">{{__('Dashboard')}}</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark" href="{{route('school.messages.index', $school)}}" aria-expanded="false">
                        <i class="mdi mdi-comment-text"></i>
                        <span class="hide-menu">{{__('Messages')}}</span>
                    </a>
                </li>

                @tenant

                    @if(StudentHelpers::hasApplicationsToSubmit())
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ SchoolHelper::getStartNewApplicationLink() }}" aria-expanded="false">
                                <i class="mdi mdi-application"></i>
                                <span class="hide-menu">{{__('Start New Application')}}</span>
                            </a>
                        </li>
                    @endif
                    {{--  @php
                        $inv = false;
                        $applications = \App\Tenant\Models\Application::all();
                        foreach ($applications as $app) {
                            if(count($app->invoices)) {
                                $inv =  true;
                            }
                        }
                    @endphp

                    @if ($inv)
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{route('finance.show', $school)}}" aria-expanded="false">
                                <!-- <i class="mdi mdi-reorder-horizontal"></i> -->
                                <span class="hide-menu">{{__('Finance')}}</span>
                            </a>
                        </li>
                    @endif  --}}
                @endtenant

            </ul>
        </nav>
    </div>
</aside>
