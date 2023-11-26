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

                <h4 class="m-t-20 m-b-20 text-center">{{__('Reset Password')}}</h4>

                @if(session()->has('success'))
                    <div class="form-group row ">
                        <div class="col-12 ">
                            <div class="alert alert-success">
                                {{session()->get('success')}}
                            </div>
                        </div>
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="form-group row ">
                        <div class="col-12 ">
                            <div class="alert alert-danger">
                                {{session()->get('error')}}
                            </div>
                        </div>
                    </div>
                @endif

                <form class="form-horizontal m-t-20" method="POST" action="{{ route('school.instructor.forgot.password' , $school) }}">
                @csrf
                <div class="form-group row ">
                    <div class="col-12 ">
                         <input id="email" type="email" class="form-control-lg form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('Email')}}">

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                    </div>
                </div>

                <div class="form-group text-center ">

                    <div class="col-xs-12 p-b-20 ">

                        <button class="btn btn-block btn-lg btn-school text-white" style="
                        background-color: {{ isset($settings['branding']['main_color']) ? $settings['branding']['main_color'] : '#2a77a6'  }}"

                        type="submit ">{{ __('Reset Password') }}</button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

            </div>

        </div>
@endsection
