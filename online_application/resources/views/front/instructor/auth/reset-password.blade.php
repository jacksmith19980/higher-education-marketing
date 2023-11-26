@extends('front.layouts.auth')

@section('content')

    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center
            auth_{{$school->slug}}"
         style="{{ (isset($settings['auth']['background'])) ? 'background:url('.Storage::disk('s3')->temporaryUrl($settings['auth']['background']['path'], \Carbon\Carbon::now()->addMinutes(5)).') no-repeat center center' : ''}} ; background-size: cover;">

        <div class="auth-box">

            <div>
                <div class="logo m-t-30">
        <span class="db">
           @if (isset($settings['branding']['logo']))
                <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="school_logo"
                     style="max-width: 180px;"/>
            @endif

        </span>
                </div>

                <!-- Form -->

                <div class="row">

                    <div class="col-12">

                        <h4 class="m-t-20 m-b-20 text-center">{{__('Change Password')}}</h4>

                        <form class="form-horizontal m-t-20" method="POST" action="{{ route('school.instructor.reset.password' , $school) }}" novalidate>

                            @csrf
                            <div class="form-group text-center ">

                                <div class="col-xs-12 p-b-20 ">
                                    <input type="hidden" name="token" value="{{$token}}">
                                    <input type="hidden" name="email" value="{{$email}}">

                                    <div class="form-group row">
                                        <div class="col-12 ">
                                            <input id="password" type="password" class="form-control-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{__('Password')}}" required>

                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback text-left">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 ">

                                            <input id="password-confirm" type="password" class="form-control-lg form-control" name="password_confirmation" placeholder="{{__('Confirm Password')}}" required>

                                        </div>
                                    </div>

                                    <button class="btn btn-block btn-lg btn-school text-white" style="
                        background-color: {{ isset($settings['branding']['main_color']) ? $settings['branding']['main_color'] : '#2a77a6'  }}"

                                            type="submit ">{{ __('Change Password') }}</button>

                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
