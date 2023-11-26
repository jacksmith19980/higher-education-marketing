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



    @include('back.layouts._partials.preloader')











    <!-- ============================================================== -->



    <!-- Main wrapper - style you can find in pages.scss -->



    <!-- ============================================================== -->



    <div class="main-wrapper">

            

        @include('front.layouts._partials.thank-you.top-nav')



                @yield('content')



    </div>

        

        @include('front.layouts.core.page-notifications.modals.form')

    <!-- ============================================================== -->



    <!-- End Wrapper -->



    <!-- ============================================================== -->



        <!-- include (Footer) -->



        @include('front.layouts._partials.parent.footer')







        <!-- include (Scripts) -->



        @include('front.layouts._partials.parent.scripts')







</body>







</html>