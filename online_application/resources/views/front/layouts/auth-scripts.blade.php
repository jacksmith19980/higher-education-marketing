<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <!-- include (Header) -->
    @include('front.layouts._partials.rounded.header')

    <meta name="_token" content="{!! csrf_token() !!}" />

    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')

    <style>
        .agent_auth_logo{
            max-width:280px !important;
        }
    </style>

    @if (isset($application->properties['custom_css']))
        <style>
            .agent_auth_logo{
                max-width:280px !important;
            }
            {!! $application->properties['custom_css'] !!}
        </style>
    @endif

</head>

<body class="school_{{$school->slug}} application_{{$application->slug}} lang_{{(\App::getLocale()) ? \App::getLocale() : 'en'}}">
    <!-- tag Manager -->
    @include('front.layouts._partials.body-tracking')

    <!-- include (preloader) -->
    @include('front.layouts._partials.rounded.preloader')


    <div class="main-wrapper" data-layout="horizontal">




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
        @include('front.layouts._partials.rounded.footer')

        <!-- include (Scriptssss) -->
        @include('front.layouts._partials.rounded.scripts')

        @if (isset($application->properties['custom_js']))
            <script>
                {!! $application->properties['custom_js'] !!}
            </script>
        @endif

</body>
</html>
