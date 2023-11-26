<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <!-- include (Header) -->
    @include('front.layouts._partials.header')
    
    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')
</head>

<body>
    
    <!-- tag Manager -->
    @include('front.layouts._partials.body-tracking')
    
    <!-- include (preloader) -->
    @include('front.layouts._partials.preloader')


    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div class="main-wrapper" data-layout="horizontal">
    
        <!-- include (TOP NAV) -->
        @include('front.layouts._partials.top-nav')


                @yield('content')


            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
      
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
        <!-- include (Footer) -->
        @include('front.layouts._partials.footer')

        <!-- include (Scripts) -->
        @include('front.layouts._partials.scripts')

</body>

</html>