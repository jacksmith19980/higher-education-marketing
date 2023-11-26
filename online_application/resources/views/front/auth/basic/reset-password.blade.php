@extends('front.layouts.auth')

@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center
            auth_{{$school->slug}}" style="{{ (isset($settings['auth']['background'])) ? 'background:url('.Storage::disk('s3')->temporaryUrl($settings['auth']['background']['path'], \Carbon\Carbon::now()->addMinutes(5)).') no-repeat center center' : ''}} ; background-size: cover;">

            <div class="auth-box on-sidebar">

<div>
<div class="logo m-t-30">
        <span class="db">
           @if (isset($settings['branding']['logo']))

            <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="school_logo" style="max-width: 80%" />

            @else
                <h3>{{$school->name}}</h3>
           @endif

        </span>
    </div>

    <!-- Form -->

    <div class="row">
        <div class="col-12">
            @include('front.auth._partials.reset-password-form')
        </div>
    </div>
</div>
            </div>

        </div>
@endsection
