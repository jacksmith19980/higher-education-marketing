<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <!-- include (Header) -->
    @include('back.layouts._partials.header')
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages/core/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages/daygrid/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages/list/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('media/libs/fullcalendar-scheduler/packages-premium/timeline/main.css') }}" rel='stylesheet' />
    @yield('styles')
    <style>
        #li2 a {
            background: #f6f6f6!important;
            color: #2d5c7a!important;
            opacity: 1!important;
        }
        #li2 a.active {
            background: #2d5c7a!important;
            color: #ffffff!important;
        }
        </style>
</head>

<body>

    <!-- include (preloader) -->
    @include('back.layouts._partials.preloader')

    <div id="main-wrapper2" data-sidebartype="overlay" data-theme="light" data-layout="vertical"
         data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- include (TOP NAV) -->
        @include('front.instructor._partials.top-nav')

        <div class="page-wrapper">

            <div style="height: 70px"></div>

            <div class="container-fluid">
                @yield('content')
            </div>

            @include('back.layouts._partials.footer')

        </div>
    </div>

    @include('back.applications._partials.page-notification')
    <!-- include (Settings) -->
    @include('back.layouts._partials.settings')
    <div class="chat-windows"></div>
    <!-- include (Scripts) -->
    @include('back.layouts._partials.scripts')
    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages/core/main.js') }}"></script>
    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages/daygrid/main.js') }}"></script>
    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages-premium/resource-common/main.js') }}"></script>
    <script src="{{ asset('media/libs/fullcalendar-scheduler/packages-premium/resource-daygrid/main.js') }}"></script>
    @yield('scripts')

</body>

</html>
