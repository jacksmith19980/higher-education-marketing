@extends('back.layouts.auth')

@section('content')

    <div class="col-md-12 col-lg-5 pl-lg-5">
        <div class="form-container login-form">
            <div class="form-header">
                <h2>Enter your email address and password to log into your account</h2>
            </div>
            <div class="form-content">
                <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <input id="email" type="email"
                               class="form-control-lg form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email" value="{{ old('email') }}" required autofocus
                               placeholder="{{__('Email')}}">

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                    <strong>{!! $errors->first('email') !!}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input id="password" type="password"
                               class="form-control-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                               name="password" required placeholder="{{__('Password')}}">

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                    <strong>{!! $errors->first('password') !!}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <p class="text-right">{{__("Forgot password?")}} <a
                                    href="/password/reset"
                                    class="m-l-5 text-link"><b>{{__("Reset Now")}}</b></a></p>
                    </div>
                    <button class="btn form-btn" type="submit "><span class="btn-text">Login</span> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M388.425,241.951L151.609,5.79c-7.759-7.733-20.321-7.72-28.067,0.04c-7.74,7.759-7.72,20.328,0.04,28.067l222.72,222.105 L123.574,478.106c-7.759,7.74-7.779,20.301-0.04,28.061c3.883,3.89,8.97,5.835,14.057,5.835c5.074,0,10.141-1.932,14.017-5.795 l236.817-236.155c3.737-3.718,5.834-8.778,5.834-14.05S392.156,245.676,388.425,241.951z" fill="#ffffff" data-original="#000000" style="" class=""/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg></button>
                </form>
            </div>
            <div class="form-footer">
                <!-- <p class="text-center">Don't have an account? Start your <a href="#">FREE TRIAL</a></p> -->
            </div>
        </div>
    </div>
@endsection

