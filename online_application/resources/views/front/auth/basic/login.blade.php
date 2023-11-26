@extends('front.layouts.auth')
@section('content')

    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center
            auth_{{$school->slug}}" style="{{ (isset($settings['auth']['background'])) ? 'background:url('.Storage::disk('s3')->temporaryUrl($settings['auth']['background']['path'], \Carbon\Carbon::now()->addMinutes(5)).') no-repeat center center' : ''}} ; background-size: cover;">
        <div class="auth-box on-sidebar">
            <div>
                <div class="logo m-t-30 m-b-30">
                    <span class="db">
                    @if (isset($settings['branding']['logo']))
                        <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="school_logo" style="max-width: 80%;" />
                    @else
                        <h3>{{$school->name}}</h3>
                    @endif
                    </span>
                </div>
                <!-- Form -->
                <div class="row">
                    @if(session()->has('success'))
                        <div class="col-12 ">
                            <div class="alert alert-success">
                                {{session()->get('success')}}
                            </div>
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="col-12 ">
                            <div class="alert alert-danger">
                                {{session()->get('error')}}
                            </div>
                        </div>
                    @endif
                    <div class="col-12 m-t-20">
                        @if (isset($settings['auth']['welcome_message']) && isset($settings['auth']['welcome_message_login']) && $settings['auth']['welcome_message_login'] == 'Yes' )
                            <div>{!! $settings['auth']['welcome_message'] !!}</div>
                        @endif
                        {{--  Login form  --}}
                        @include('front.auth._partials.login-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
