<h4 class="m-t-20 m-b-20 text-center">{{__('Change Password')}}</h4>

<form class="form-horizontal m-t-20" method="POST" action="{{ route('school.reset.password' , $school) }}" novalidate>

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

                <input id="password-confirm" type="password" class="form-control-lg form-control" name="password_confirmation" placeholder="{{__('Confirm password')}}" required>

            </div>
        </div>

        <button class="btn btn-block btn-lg btn-school text-white" style="
        background-color: {{ isset($settings['branding']['main_color']) ? $settings['branding']['main_color'] : '#2a77a6'  }}"

        type="submit ">{{ __('Change password') }}</button>

    </div>

</div>

</form>
