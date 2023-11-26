@extends('back.layouts.auth')

@section('content')
<div>
                    <div class="logo m-t-30">
                        <span class="db"><img src="{{ asset('media/images/hem_logo.png') }}" alt="logo" /></span>
                    </div>

                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            <form class="form-horizontal m-t-20" method="POST" action="{{ route('register') }}">
                                @csrf
                                
                                <div class="form-group row ">
                                    <div class="col-12 ">
                                        <input placeholder="{{ __('Name') }}" id="name" type="text" class="form-control-lg form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('name') }}</strong>
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

{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-12 ">--}}
{{--                                        <input id="team" type="text" class="form-control-lg form-control{{ $errors->has('team') ? ' is-invalid' : '' }}" name="team" value="{{ old('team') }}" placeholder="{{__('Team name')}}" required>--}}

{{--                                        @if ($errors->has('team'))--}}
{{--                                            <span class="invalid-feedback">--}}
{{--                                                <strong>{{ $errors->first('team') }}</strong>--}}
{{--                                            </span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="form-group row">
                                    <div class="col-12 ">
                                      <input id="password" type="password" class="form-control-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{__('Password')}}" required>

                                        <small class="form-text text-info float-left helper-text">
                                            {{__('At least one letter, uppercase, number, symbol and a minimum of 8 characters')}}
                                        </small><br />

                                        @if ($errors->has('password'))
                                            @foreach ($errors->get('password') as $message)
                                                <span class="invalid-feedback" role="alert" style="display:block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @endforeach
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
                                            <label class="custom-control-label" for="customCheck1">{{__('I agree to all ')}}<a href="javascript:void(0)">{{__('Terms')}}</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center ">
                                    <div class="col-xs-12 p-b-20 ">
                                        <button class="btn btn-block btn-lg btn-info " type="submit ">{{ __('Register') }}</button>
                                    </div>
                                </div>
                                <div class="form-group m-b-0 m-t-10 ">
                                    <div class="col-sm-12 text-center ">
                                        {{__('Already have an account? ')}}<a href="{{ route('login') }}" class="text-info m-l-5 "><b>{{__('Sign In')}}</b></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
@endsection
