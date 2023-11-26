<!DOCTYPE html>

<html dir="ltr" lang="en">

<head>
   {{-- @include('front.agent._partials.header') --}}
   <!-- include (Header) -->
    @include('back.layouts._partials.header')
    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')
</head>

    <body>
        @include('front.layouts._partials.body-tracking')

        @include('back.layouts._partials.preloader')

        <div id="main-wrapper">

            @include('front.agent._partials.top-nav')

            <!-- include Side Bar -->
            @include('front.agent._partials.side-bar')

            @yield('content')

        </div>

            @include('back.layouts.core.page-notifications.modals.form')

            @include('back.layouts._partials.footer')

            @include('front.applications._partials.page-notification')

            <!-- include (Scripts) -->
            @include('front.agent._partials.scripts')

            {{-- @include('front.layouts._partials.parent.scripts') --}}

    </body>
</html>