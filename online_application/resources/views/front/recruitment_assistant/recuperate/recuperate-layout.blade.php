<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('front.layouts._partials.head-tracking')

    <link rel="stylesheet" href="{{ asset('media/css/quotation/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('media/css/quotation/fontawesome/css/all.min.css') }}">

    <!-- Custom CSS -->
    <link href="{{ asset('media/css/quotation/noty.css') }}" rel="stylesheet">
    <link href="{{ asset('media/css/quotation/app.css') }}" rel="stylesheet">

    <title>VAA Summary</title>
    <script src="{{ asset('media/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('media/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="https://momentjs.com/downloads/moment.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>


    <script src="{{ asset('media/extra-libs/inter-phone/phone.js') }}" async></script>

    <script src="{{ asset('media/css/quotation/fontawesome/js/all.min.js') }}" async></script>
    <script src="{{ asset('media/js/quotation/noty.min.js') }}" async></script>
    <script src="{{ asset('media/js/quotation/app.js') }}" async></script>

    @include('front.layouts._partials.general.settings-colors')

</head>
<body>
@include('front.layouts._partials.body-tracking')

<!-- Header -->
<header class="header-main">
    <div class="logo-container py-3">
        @if (isset($settings['branding']['logo']))
            <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="mx-auto logo"
            />
        @endif
    </div>
    <div class="header-headline">
        <h1>{{$assistant_builder->title}}</h1>
    </div>
    <div class="header-nav">
        <div class="container">
            <div class="header-nav-container">
                <nav>
                    <ul>
                        <li class="active">
                            <a  href="#">1</a>
                            <span class="nav-label">{{__('VAA Summary')}}</span>
                        </li>
                        <li>
                            <a  href="#">2</a>
                            <span class="nav-label">{{__('Apply')}}</span>
                        </li>
                    </ul>
                    <div class="nav-hline-accent"></div>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- End Header -->

<div>
    @yield('content')
</div>

<footer>
    <p>{{date('Y')}} {{__('All Rights Reserved by')}} <a href="{{$settings['school']['website']}}" target="_blank">{{$school->name}}</a>.</p>
</footer>
</body>
</html>
