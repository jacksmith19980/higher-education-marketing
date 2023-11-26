<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    @include('back.layouts._partials.header')
    @yield('styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">

</head>

<body>

    <!-- include (preloader) -->
    @include('back.layouts._partials.preloader')


    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">

        <!-- include (TOP NAV) -->
        @include('back.layouts._partials.top-nav')


        <!-- include (Side Bar) -->
        @include('back.layouts._partials.side-bar')


        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div @if(isset($background))class="page-wrapper-blue" @else class="page-wrapper" @endif>

            <!-- include (Bread Crum) -->
            @include('back.layouts._partials.breadcrumb')

            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->



            <div class="container-fluid" style="min-height:80vh">

                <!-- ============================================================== -->
                <!-- Start Flash Content -->
                <!-- ============================================================== -->

                @include('back.layouts._partials.flash-message')

                <!-- ============================================================== -->
                <!-- End Flash Content -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                @yield('content')

                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->

            <!-- include (Footer) -->
            @include('back.layouts._partials.footer')


            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    @include('back.applications._partials.page-notification')

    <!-- include (Settings) -->
    @include('back.layouts._partials.settings')

    @yield('right-panel')

    <div class="chat-windows"></div>

    <!-- include (Scripts) -->
    @include('back.layouts._partials.scripts')
    @yield('scripts')

</body>

</html>
