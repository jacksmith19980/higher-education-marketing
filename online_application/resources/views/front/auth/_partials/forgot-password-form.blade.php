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
            <div class="alert alert-danger bb">
                {{ __(session()->get('error')) }}
            </div>
        </div>
    </div>
@endif
<form class="form-horizontal m-t-20" method="POST" action="{{ route('school.forgot.password' , $school) }}">
    @csrf
    <div class="form-group row ">
        <div class="col-12 ">
                <input id="email" type="email" class="form-control-lg form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('Email')}}">

                @if ($errors->has('email'))
                    <span class="invalid-feedback bb">
                        <strong>{!!__( $errors->first('email') )!!}</strong>
                    </span>
                @endif
        </div>
    </div>

    <div class="form-group text-center ">

        <div class="col-xs-12 p-b-20 ">

            <button class="btn btn-block btn-lg btn-school text-white" style="
            background-color: {{ isset($settings['branding']['main_color']) ? $settings['branding']['main_color'] : '#2a77a6'  }}"

            type="submit ">{{ __('Reset password') }}</button>

        </div>

    </div>

</form>
