<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <!-- include (Header) -->
    @include('back.layouts._partials.header')

    <script type="text/javascript">window.frontAjaxRoute = "{{route('front.ajax' , ['school' => $school])}}";</script>

    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')

    <style>
        .strip-payment-btn{
        width:100%;
        padding:20px;
        font-size: 20px;
        font-weight: bold;
        background-color: #17bdb6;
        color:#fff;
        cursor: pointer;
        border-radius: 3px;
    }
    </style>

    <link href="{{ asset('media/libs/filepond/css/filepond.css') }}" rel="stylesheet">

    <link href="{{ asset('media/extra-libs/jodit-3.2.44/build/jodit.min.css') }}" rel="stylesheet">

    @if(isset($application))
        {{-- @include('front.layouts._partials.'.$application->layout.'.header') --}}
        @if (isset($application->properties['custom_css']))
            <style>
                {!! $application->properties['custom_css'] !!}

            </style>
        @endif
    @endif


</head>


<body>
    <!-- tag Manager -->
    @include('front.layouts._partials.body-tracking')

    <!-- include (preloader) -->
    @include('back.layouts._partials.preloader')



    <!-- ============================================================== -->

    <!-- Main wrapper - style you can find in pages.scss -->

    <!-- ============================================================== -->

    <!-- <div class="main-wrapper"> -->
        <div id="main-wrapper">


        <!-- include (TOP NAV) -->

        @include('front.layouts._partials.oiart.top-nav')

        <!-- include Side Bar -->
        @include('front.layouts._partials.oiart.side-bar')


                @yield('content')

    </div>

        @include('front.layouts.core.page-notifications.modals.form')
    <!-- ============================================================== -->

    <!-- End Wrapper -->

    <!-- ============================================================== -->

        <!-- include (Footer) -->

        @include('back.layouts._partials.footer')
        @include('front.applications._partials.page-notification')

        <script src="{{ asset('media/libs/filepond/js/filepond.js') }}"></script>

        <!-- include (Scripts) -->
        @include('front.layouts._partials.parent.scripts')

        @if(isset($application))
            @if (isset($application->properties['custom_js']))
                <script>
                    {!! $application->properties['custom_js'] !!}
                </script>
            @endif
        @endif

        @yield('messages')
</body>



</html>
