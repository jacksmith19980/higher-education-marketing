@extends('front.layouts.auth')

@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center auth_{{$school->slug}}" style="
    @settings(['auth.background'])
        {{'background:url('.Storage::disk('s3')->temporaryUrl($settings['auth']['background']['path'], \Carbon\Carbon::now()->addMinutes(5)).') no-repeat center center;'  }}
    @endsettings
background-size: cover;">
    <div class="auth-box" style="max-width:700px;">
        <div>
            <div class="logo m-t-30">
                <span class="db" style="position: relative">
                    @settings(['school.logo'])
                        <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="agent_auth_logo"/>
                    @endsettings
                </span>
            </div>

            <div class="row">
                <div class="col-12">
                    <form class="form-horizontal m-t-20" method="POST" action="{{ route('school.agent.register' , $school) }}">
                        @csrf
                        @if(session('success'))
                            <div class="form-group row">
                                <div class="col-12 ">
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (isset($params))
                            @foreach ($params as $key=>$val)
                                <input type="hidden" name="{{$key}}" value="{{$val}}" />
                            @endforeach
                        @endif

                        @if(request('company') )
                            <input type="hidden" name="agency" value="{{ key($agencies) }}">
                        @else
                            <div id="createAgency" class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input', [
                                        'name'          => 'agency_name',
                                        'label'         => 'Agency Name' ,
                                        'class'         => '' ,
                                        'required'      => true,
                                        'attr'          => '',
                                        'value'         => old('agency_name') ,
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.email-input', [
                                        'name'          => 'agency_email',
                                        'label'         => 'Agency Email' ,
                                        'class'         => '' ,
                                        'required'      => true,
                                        'attr'          => 'onkeydown=app.checkAgencyEmail(this) onblur=app.checkAgencyEmail(this) onkeyup=app.checkAgencyEmail(this) onchange=app.checkAgencyEmail(this)',
                                        'value'         => old('agency_email') ,
                                    ])
                                    <div id="agency_message"></div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                @include('back.layouts.core.forms.text-input', [
                                    'name'          => 'first_name',
                                    'label'         => 'First Name' ,
                                    'class'         => '' ,
                                    'required'      => true,
                                    'attr'          => '',
                                    'value'         => old('first_name') ,
                                ])
                            </div>
                            <div class="col-md-6">
                                @include('back.layouts.core.forms.text-input', [
                                    'name'          => 'last_name',
                                    'label'         => 'Last Name' ,
                                    'class'         => '' ,
                                    'required'      => true,
                                    'attr'          => '',
                                    'value'         => old('last_name') ,
                                ])
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                @include('back.layouts.core.forms.email-input', [
                                    'name'          => 'email',
                                    'label'         => 'Your Email' ,
                                    'class'         => '' ,
                                    'required'      => true,
                                    'attr'          => '',
                                    'value'         => old('email') ,
                                ])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                @include('back.layouts.core.forms.password', [
                                    'name'          => 'password',
                                    'label'         => 'Password' ,
                                    'class'         => '' ,
                                    'required'      => true,
                                    'attr'          => '',
                                    'value'         => '' ,
                                ])
                            </div>
                            <div class="col-md-6">
                                @include('back.layouts.core.forms.password', [
                                    'name'          => 'password_confirmation',
                                    'label'         => 'Confirm Password' ,
                                    'class'         => '' ,
                                    'required'      => true,
                                    'attr'          => '',
                                    'value'         => '' ,
                                ])
                            </div>
                        </div>

                        @settings(['auth.terms_conditions'])
                            <div class="form-group row">
                                <div class="col-md-12 ">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" required>
                                        <label class="custom-control-label" for="customCheck1">{{__('I agree to all ')}}<a href="javascript:void(0)" class="text-link">{{__('Terms')}}</a></label>
                                    </div>
                                </div>
                            </div>
                        @endsettings

                        @if (isset($settings['auth']['enable_recaptcha']) && $settings['auth']['enable_recaptcha'] == "Yes" && !empty($settings['auth']['recaptcha_site_key']))
                            <div class="g-recaptcha" data-sitekey="{{$settings['auth']['recaptcha_site_key']}}"></div>
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="invalid-feedback" style="display: block">
                                    <strong>{{__('Please check the the captcha.')}}</strong>
                                </span>
                            @endif
                            <div style="display:block;margin-bottom:15px;"></div>
                        @endif

                        <div class="form-group text-center ">
                            <div class="col-xs-12 p-b-20 ">
                                <button class="btn btn-block btn-lg btn-school text-white" style="
                                @settings(['school.main_color'])
                                    {{'background-color: '.$settings['branding']['main_color'] }}
                                @endsettings
                                " type="submit ">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                        <div class="form-group m-b-0 m-t-10 ">
                            <div class="col-sm-12 text-center ">
                                {{__('Already have an account? ')}} <a href="{{ route('school.agent.login' , $school) }}" class="text-link m-l-5 "><b>{{__('Sign In')}}</b></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
