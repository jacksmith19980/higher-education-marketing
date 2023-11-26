<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <!-- include (Header) -->
    @include('back.layouts._partials.header')
    @yield('styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
    <style>
        .sidebar-item a{ /*display:none !important; */}
        .overlay-body{
            position: absolute;
            background-color: #0000007d;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99;
        }

        .card{
            z-index: 999;
        }
    </style>
    <script type="text/javascript">
        window.setTimeout(function() {
            window.location.href = '{{ URL::to("/settings#itour") }}';
            /*window.location = 'https://application.crmforschools.net/settings#itour';*/
        }, 10000);
    </script>
</head>

<body>
    <div class="overlay-body"></div>

<!-- include (preloader) -->
@include('back.layouts._partials.preloader')


<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">

    <!-- include (TOP NAV) -->
@include('back.layouts._partials.top-nav-thankyou')


<!-- include (Side Bar) -->
@include('back.layouts._partials.side-bar-thankyou')


<!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">

        <!-- include (Bread Crum) -->
    @include('back.layouts._partials.breadcrumb')

    <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->



        <div class="container-fluid">

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


<div class="chat-windows"></div>

<!-- include (Scripts) -->
@include('back.layouts._partials.scripts')
@yield('scripts')

</body>

</html>