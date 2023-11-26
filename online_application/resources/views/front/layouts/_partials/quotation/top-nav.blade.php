<header class="topbar">



    <nav class="navbar top-navbar navbar-expand-md navbar-light">



        <div class="navbar-header">

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



        <div class="navbar-collapse collapse" id="navbarSupportedContent">

        </div>



    </nav>



</header>