@extends('front.layouts.auth')

@section('content')


<div class="auth-wrapper d-flex no-block justify-content-center align-items-center
            auth_{{$school->slug}}" style="{{ (isset($settings['auth']['background'])) ? 'background:url('.Storage::disk('s3')->temporaryUrl($settings['auth']['background']['path'], \Carbon\Carbon::now()->addMinutes(5)).') no-repeat center center' : ''}} ; background-size: cover;">

            <div class="auth-box">

<div>
                    <div class="logo m-t-30">
                        <span class="db">
                            @if (isset($settings['branding']['logo']))
                               <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="school_logo" style="max-width: 180px;" />
                            @endif
                        </span>
                    </div>

                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            <form class="form-horizontal m-t-20" method="POST" action="{{ route('school.agent.register' , $school) }}">
                                @csrf


                                @if (session('success'))
                                 <div class="form-group row ">
                                    <div class="col-12 ">
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    </div>
                                </div>
                                @endif


                                <div class="form-group row ">
                                    <div class="col-12 ">
                                        <input placeholder="{{ __('First Name') }}" id="first_name" type="text" class="form-control-lg form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                        @if ($errors->has('first_name'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                 <div class="form-group row ">
                                    <div class="col-12 ">
                                        <input placeholder="{{ __('Last Name') }}" id="first_name" type="text" class="form-control-lg form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required autofocus>

                                        @if ($errors->has('last_name'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-12 ">
                                     <input id="email" type="email" class="form-control-lg form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{__('Email')}}" required>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 ">
                                      <input id="password" type="password" class="form-control-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{__('Password')}}" required>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback">
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
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">{{__('I agree to all ')}}<a href="javascript:void(0)" class="text-link">{{__('Terms')}}</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center ">
                                    <div class="col-xs-12 p-b-20 ">
                                        <button class="btn btn-block btn-lg btn-school text-white" style="
                        {{($settings['branding']['main_color'])? 'background-color: '.$settings['branding']['main_color'].'' : ''}}
                        " type="submit ">
                                {{ __('Register') }}</button>
                                    </div>
                                </div>
                                <div class="form-group m-b-0 m-t-10 ">
                                    <div class="col-sm-12 text-center ">
                                        {{__('Already have an account? ')}}<a href="{{ route('school.agent.login' , $school) }}" class="text-link m-l-5 "><b>{{__('Sign In')}}</b></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
                </div>
@endsection
