<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="quotation" content="{{ request('quotation')->slug }}">

    <meta name="first_step" content="{{ $firstStep }}">

    <meta name="step" content="{{ $step }}">
    @include('front.layouts._partials.head-tracking')

    <link rel="stylesheet" href="{{ asset('media/css/quotation/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('media/css/quotation/fontawesome/css/all.min.css') }}">

    <!-- Custom CSS -->
    <link href="{{ asset('media/css/quotation/noty.css') }}" rel="stylesheet">
    <link href="{{ asset('media/css/quotation/app.css') }}" rel="stylesheet">

    <title>Quotations</title>
            <script src="{{ asset('media/libs/jquery/dist/jquery.min.js') }}"></script>
            <script src="{{ asset('media/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
            <script src="https://momentjs.com/downloads/moment.min.js"></script>

{{--            <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>--}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.1/js.cookie.min.js"></script>


            <script src="{{ asset('media/extra-libs/inter-phone/phone.js') }}" async></script>

            <script src="{{ asset('media/css/quotation/fontawesome/js/all.min.js') }}" async></script>
            <script src="{{ asset('media/js/quotation/noty.min.js') }}" async></script>
            <script src="{{ asset('media/js/quotation/app.js') }}" async></script>

    @include('front.layouts._partials.general.settings-colors')

</head>
<body>
@include('front.layouts._partials.body-tracking')
<header class="header-main">
    <div class="logo-container py-3">
        @if (isset($settings['branding']['logo']))
            <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="mx-auto logo"
            />
        @endif
    </div>
    <div class="header-headline">
        <h1>{{$quotation->title}}</h1>
    </div>
    <div class="header-nav">
        <div class="container">
            <div class="header-nav-container">
                <nav>
                    <ul>
                        @foreach ($tabs as $order =>$tab)
                        @php
                            $prevStep = ($order != 0) ? $tabs[$order - 1] : $tabs[$order]
                        @endphp
                        <li class="{{ ( $step == $tab['step'] ) ? 'active' : ''}} ">
                            <a  href="javascript:void(0)" data-step="{{$prevStep['step']}}" data-route="{{ route('quotations.show' , [
                                'school'        => $school,
                                'quotation'     => $quotation,
                                'step'          => $tab['step']
                                ]) }}" onclick="app.isValid(this)" data-message="{{$prevStep['error_message']}}">{{$order + 1 }}</a>

                            <span class="nav-label">{{__($tab['step_title'])}}</span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="nav-hline-accent"></div>
                </nav>
            </div>
        </div>
    </div>
</header>
<div>
    @yield('content')
</div>

<footer>
    <p>{{date('Y')}} {{__('All Rights Reserved by')}} <a href="{{$settings['school']['website']}}" target="_blank">{{$school->name}}</a>.</p>
</footer>
</body>
</html>
