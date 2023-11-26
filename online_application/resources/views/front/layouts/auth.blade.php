<!DOCTYPE html>
<html dir="ltr">

<head>
    @include('front.layouts._partials.auth.header')
    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')
    @if (isset($settings['school']['global_custom_css']))
    <style>
        {!! $settings['school']['global_custom_css'] !!}
    </style>
    @endif

    <script type="text/javascript">window.ajaxRoute = "{{route('front.ajax' , ['school' => $school])}}";</script>
</head>


<body class="@yield('authType')">

    <!-- tag Manager -->
    @include('front.layouts._partials.body-tracking')

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

   @if ( isset($settings['auth']['enable_recaptcha']) && $settings['auth']['enable_recaptcha'] == "Yes" &&
        !empty($settings['auth']['recaptcha_site_key']))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif


    @include('front.layouts._partials.auth.scripts')


    <script>
        $('[data-toggle="tooltip "]').tooltip();

    $(".preloader ").fadeOut();

    </script>

</body>



</html>
