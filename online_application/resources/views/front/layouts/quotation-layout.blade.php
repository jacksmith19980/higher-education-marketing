<!DOCTYPE html>
<html dir="ltr">

<head>
    @include('front.layouts._partials.header')
    
    <link href="{{ asset('media/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">    
    <script type="text/javascript">window.quotationAjaxRoute = "{{route('quotation.update.price' , $school)}}";</script>
    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')

</head>



<body>
   <!-- tag Manager -->
   @include('front.layouts._partials.body-tracking')
   @include('front.layouts._partials.quotation.top-nav')

    <div class="main-wrapper">

        <!-- ============================================================== -->

        <!-- Preloader - style you can find in spinners.css -->

        <!-- ============================================================== -->

        <div class="preloader">

            <div class="lds-ripple">

                <div class="lds-pos"></div>

                <div class="lds-pos"></div>

            </div>

        </div>

        <!-- ============================================================== -->

        <!-- Preloader - style you can find in spinners.css -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Login box.scss 222 -->

        <!-- ============================================================== -->

        
                

                 @yield('content')

               


        <!-- ============================================================== -->

        <!-- Login box.scss -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Page wrapper scss in scafholding.scss -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Page wrapper scss in scafholding.scss -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Right Sidebar -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Right Sidebar -->

        <!-- ============================================================== -->

    </div>

    @include('front.quotation._partials.layouts.scripts')



    <script>

    $('[data-toggle="tooltip "]').tooltip();

    $(".preloader ").fadeOut();

    </script>

</body>



</html>