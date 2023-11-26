<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <!-- include (Header) -->
    @include('back.layouts._partials.header')

    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')

</head>







<body>

    <!-- tag Manager -->

    @include('front.layouts._partials.body-tracking')



    



    <!-- include (preloader) -->



    
    <div class="main-wrapper" style="display:block">

            


    @include('front.layouts._partials.payment.top-nav')

                @yield('content')



    </div>


    <!-- ============================================================== -->



    <!-- End Wrapper -->



    <!-- ============================================================== -->



        <!-- include (Footer) -->



        @include('front.layouts._partials.payment.footer')







        <!-- include (Scripts) -->

        @include('front.layouts._partials.payment.scripts')







</body>







</html>