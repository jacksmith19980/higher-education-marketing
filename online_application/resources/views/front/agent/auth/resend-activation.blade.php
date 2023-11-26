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

                 <form class="form-horizontal m-t-20" method="POST" action="{{ route('school.agent.resend.activation' , $school) }}">

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
                    <div class="col-md-12">
                        @include('back.layouts.core.forms.email-input',
                        [
                            'name'          => 'email',
                            'label'         => 'Email' ,
                            'class'         => '' ,
                            'required'      => true,
                            'attr'          => '',
                            'value'         => old('email') ,
                        ])
                    </div>
                </div>

                <div class="form-group text-center ">
                    <div class="col-xs-12 p-b-20 ">
                        <button class="btn btn-block btn-lg btn-school text-white" style="
                        {{($settings['branding']['main_color'])? 'background-color: '.$settings['branding']['main_color'].'' : ''}}
                        " type="submit ">{{ __('Resend Activation Email') }}</button>
                    </div>

                </div>
               <div class="form-group m-b-0 m-t-10">
                    <div class="col-sm-12 text-center">
                        {{__("Don't have an account?")}} <a href="{{route('school.agent.register' , $school)}}" class="m-l-5 text-link"><b>{{__("Sign Up")}}</b></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

            </div>

        </div>
@endsection
