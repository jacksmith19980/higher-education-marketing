<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <!-- include (Header) -->
    @include('front.layouts._partials.'.$application->layout.'.header')

    <meta name="_token" content="{!! csrf_token() !!}" />

    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')

    @if (isset($application->properties['custom_css']))
        <style>
            {!! $application->properties['custom_css'] !!}
        </style>
    @endif

</head>

<body class="school_{{$school->slug}} application_{{$application->slug}}">

    <!-- tag Manager -->
    @include('front.layouts._partials.body-tracking')

    <!-- include (preloader) -->
{{--    @include('front.layouts._partials.'.$application->layout.'.preloader')--}}


    <div class="main-wrapper" data-layout="horizontal">


        @if ( isset($student) && !isset($application->properties['no_login']))

{{--            @include('front.layouts._partials.'.$application->layout.'.top-nav')--}}

        @endif

        @yield('content')

        @if (isset($application->properties['show_instructions']) && $application->properties['show_instructions'] == 1)

        <button type="button" class="btn btn-danger btn-circle instructions-btn" onclick="app.startModal(this)"
            data-route='{{route("application.instructions" , ["school"=>$school , "application" => $application])}}'
            data-title="Application's Instructions">
            <i class="icon-question"></i>
        </button>

        @endif

    </div>
         @include('front.layouts.core.page-notifications.modals.form')
        <!-- include (Footer) -->
{{--        @include('front.layouts._partials.'.$application->layout.'.footer')--}}

        <!-- include (Scriptssssssssss) -->
        @include('front.layouts._partials.'.$application->layout.'.scripts')

        @if (isset($application->properties['custom_js']))
            <script>
                {!! $application->properties['custom_js'] !!}
            </script>
        @endif

</body>
</html>
