@extends('front.layouts.auth')

@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center
            auth_{{$school->slug}}"
    style="{{ (isset($settings['auth']['background'])) ? 'background:url('.Storage::disk('s3')->temporaryUrl($settings['auth']['background']['path'], \Carbon\Carbon::now()->addMinutes(5)).') no-repeat center center' : ''}} ; background-size: cover;">
    @if(!empty($settings['auth']['background_color_one']) && !empty($settings['auth']['background_color_two']))
    <div class="background-overlay"
        style="background-image: linear-gradient(140deg, {!! $settings['auth']['background_color_one'] !!} 70%, {!! $settings['auth']['background_color_two'] !!} 70%);height: 100%;width: 100%;top: 0;left: 0;position: absolute;opacity: .50;">
    </div>
    @elseif(!empty($settings['auth']['background_color_two']))
    <div class="background-overlay"
        style="background-image: linear-gradient(140deg,#162E40 70%,{!! $settings['auth']['background_color_two'] !!} 70%);height: 100%;width: 100%;top: 0;left: 0;position: absolute;opacity: .50;">
    </div>
    @else
    <div class="background-overlay"
        style="background-image: linear-gradient(140deg,#162E40 70%,#0AB9B2 70%);height: 100%;width: 100%;top: 0;left: 0;position: absolute;opacity: .50;">
    </div>
    @endif

    <div class="container">
        <div class="row mx-3">
            <div class="col-md-12">
                <div class="logo m-b-10">
                    <span class="db">
                        @php $locale = Config::get('app.locale') @endphp
                        @if($locale)
                        @if(isset($settings['branding']['logos'][$locale]['path']))
                        <img src="{{ Storage::disk('s3')->temporaryUrl($settings['branding']['logos'][$locale]['path'], \Carbon\Carbon::now()->addMinutes(5)) }}" />
                        @endif
                        @else
                            <h3>{{$school->name}}</h3>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="row mx-3">
            <div class="col-md-6">
                <div class="m-b-70 d-none d-sm-none d-md-block"></div>
                <h1 class="login-title"
                    style="color: @if (isset($settings['auth']['login_title_text_color'])) {!! $settings['auth']['login_title_text_color'] !!}; @else #000; @endif">
                    {!! isset($settings['auth']['welcome_login_title_text']) ?
                    __($settings['auth']['welcome_login_title_text']) : '' !!}
                </h1>
                <h5 class="login-tagline"
                    style="color: @if (isset($settings['auth']['login_desc_text_color'])) {!! $settings['auth']['login_desc_text_color'] !!}; @else #000; @endif ;">
                    {!! isset($settings['auth']['welcome_login_desc_text']) ?
                    __($settings['auth']['welcome_login_desc_text']) : '' !!}
                </h5>
            </div>

            <div class="col-md-6">
                @if(empty($settings['auth']['background_color_one']) ||
                empty($settings['auth']['background_color_two']))
                <div class="register-form-box"
                    style="border: 4px solid #162E40; background-color:@if(isset($settings['auth']['background_color'])){!! $settings['auth']['background_color'] !!}; @else #fff; @endif">
                    @else
                    <div class="login-form-box"
                        style="border: 4px solid {!! $settings['auth']['background_color_one'] !!}; background-color:@if(isset($settings['auth']['background_color'])){!! $settings['auth']['background_color'] !!}; @else #fff; @endif">
                        @endif
                        @if(session()->has('success'))
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                {{session()->get('success')}}
                            </div>
                        </div>
                        @endif

                        @if(session()->has('error'))
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                {{session()->get('error')}}
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            @if (isset($settings['auth']['welcome_message']) &&
                            isset($settings['auth']['welcome_message_login']) &&
                            $settings['auth']['welcome_message_login'] == 'Yes' )
                            <div>{!! $settings['auth']['welcome_message'] !!}</div>
                            @endif

                            @include('front.auth._partials.login-form')

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endsection
