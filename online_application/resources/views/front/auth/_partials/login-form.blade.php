<form class="form-horizontal" method="POST" action="{{ route('school.login' , $school) }}">
    @csrf

    @if (isset($_GET))
        @foreach ($_GET as $key=>$val)
            <input type="hidden" name="{{$key}}" value="{{$val}}" />
        @endforeach
    @endif

    <div class="form-group row ">
        <div class="col-12 ">

            <input id="email" type="email" class="form-control-lg form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('Email')}}">



            @if ($errors->has('email'))

                <span class="invalid-feedback">

            <strong>{{ __($errors->first('email')) }}</strong>

        </span>

            @endif





        </div>

    </div>

    <div class="form-group row">
        <div class="col-12 ">
            <input id="password" type="password" class="form-control-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{__('Password')}}">
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ __($errors->first('password')) }}</strong>
                </span>
            @endif
        </div>
    </div>

    @if ( isset($settings['auth']['enable_recaptcha']) && $settings['auth']['enable_recaptcha'] == "Yes" &&
    !empty($settings['auth']['recaptcha_site_key']))
        <div class="g-recaptcha" data-sitekey="{{$settings['auth']['recaptcha_site_key']}}"></div>
        @if ($errors->has('g-recaptcha-response'))
            <span class="invalid-feedback" style="display: block">
                <strong>{{__('Please check the the captcha.')}}</strong>
            </span>
        @endif
        <div style="display:block;margin-bottom:15px;"></div>
    @endif

    <div class="form-group m-b-10 m-t-10">
        <div class="col-sm-12 text-right">
            {{__("Forgot password?")}} <a href="{{route('school.forgot.password' , $school)}}" class="m-l-5 text-link"><b>{{__("Reset now")}}</b></a>
        </div>
    </div>

    <div class="form-group text-center ">
        <div class="col-xs-12 p-b-20 ">
            <button class="btn btn-block btn-lg btn-school text-white" style="background-color: {{ (isset($settings['branding']['main_color']) && $settings['branding']['main_color'] !== '') ? $settings['branding']['main_color'] : '#2a77a6'  }}"
                    type="submit ">{{ __('Login') }}</button>
        </div>
    </div>
    <div class="form-group m-b-0 m-t-10">
        <div class="col-sm-12 text-center">
            {{__("Don't have an account?")}} <a href="{{route('school.register' , $params)}}" class="m-l-5 text-link"><b>{{__("Sign up")}}</b></a>
        </div>
    </div>
</form>
