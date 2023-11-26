<!DOCTYPE html>

<html dir="ltr" lang="en">



<head>
    <!-- include (Header) -->
    @include('back.layouts._partials.header')
</head>



<body>



    <!-- include (preloader) -->

    @include('back.layouts._partials.preloader')





    <!-- ============================================================== -->

    <!-- Main wrapper - style you can find in pages.scss -->

    <!-- ============================================================== -->

    <div class="main-wrapper">


        <!-- include (TOP NAV) -->

        @include('back.layouts._partials.top-nav')

                @yield('content')

    </div>

    <!-- ============================================================== -->

    <!-- End Wrapper -->

    <!-- ============================================================== -->

        <!-- include (Footer) -->

        @include('back.layouts._partials.footer')



        <!-- include (Scripts) -->

        @include('back.layouts._partials.scripts')



</body>



</html>
