@php
    $campus = (isset($_GET['campus']) ? $_GET['campus'] : ((isset($params['campus'])) ? $params['campus'] : false));
@endphp
<form class="form-horizontal m-t-20" method="POST" action="{{ route('school.register' , $school) }}">
        @csrf
        @if (isset($_GET))
            @foreach ($_GET as $key=>$val)
                <input type="hidden" name="{{$key}}" value="{{$val}}"/> @endforeach
        @endif

        @if (isset($params))
            @foreach ($params as $key=>$val)
                <input type="hidden" name="{{$key}}" value="{{$val}}" />
            @endforeach
        @endif

        <div class="form-group row">
            <div class="col-12 ">
                <input placeholder="{{ __('First Name') }}" id="first_name" type="text" class="form-control-lg form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                        name="first_name" value="{{ old('first_name') }}" required autofocus>
                @if ($errors->has('first_name'))
                    <span class="invalid-feedback">
                    <strong>{{ __($errors->first('first_name')) }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 ">
                <input placeholder="{{ __('Last Name') }}" id="last_name" type="text" class="form-control-lg form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                        name="last_name" value="{{ old('last_name') }}" required
                        autofocus>
                @if ($errors->has('last_name'))
                    <span class="invalid-feedback">
                        <strong>{{ __($errors->first('last_name')) }}</strong>
                    </span>
                @endif
            </div>
        </div>
        @if(isset($settings['auth']['phone_register']) && $settings['auth']['phone_register'] == 'Yes')
            <div class="form-group row ">
                <div class="col-12 ">
                    <input placeholder="{{ __('Phone') }}" id="phone"
                            type="phone"
                            data-lang="{{App::getLocale()}}"
                            data-country-code="{{(isset($settings['auth']['default_country']))? $settings['auth']['default_country'] : 'CAN'}}"
                            class="form-control-lg form-control inter-calling-code-mode {{ $errors->has('phone') ? ' is-invalid' : '' }}"
                            name="phone" value="{{ old('phonefield_phone') }}" required
                            autofocus>

                    @if ($errors->has('phone'))
                        <span class="invalid-feedback">
                            <strong>{{ __($errors->first('phone')) }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        @endif
        <div class="form-group row">
            <div class="col-12 ">
                <input id="email" type="email"
                        class="form-control-lg form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                        name="email" value="{{ old('email') }}"
                        placeholder="{{__('Email')}}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ __($errors->first('email')) }}</strong>
                        </span>
                @endif
            </div>
        </div>

        @if(isset($settings['campuses']) && count($settings['campuses']) > 1 && isset($settings['auth']['show_campus']) && $settings['auth']['show_campus'] == 'Yes' && !$campus)

        <div class="row">
            <div class="col-12 ">

                @include('back.layouts.core.forms.select',
                    [
                    'name'          => 'campus',
                    'label'         => '',
                    'placeholder'   => 'Select Campus',
                    'class'         => '' ,
                    'required'      => true,
                    'attr'          => 'onchange=app.programFilter(this)',
                    'data'          => ProgramHelpers::campusesList(),
                    'value'         => old('campus'),
                    ])
            </div>
        </div>

        @elseif( isset($settings['campuses']) && count($settings['campuses']) == 1 && isset($settings['auth']['show_campus']) && $settings['auth']['show_campus'] == 'Yes')

            @php
                $campus = reset($settings['campuses'])['id'];
            @endphp
            <input type="hidden" name="campus" value="{{$campus}}" />

        @endif


        <?php if( !isset($params['program'])  || empty($params['program'])): ?>

        <div id="programsListContainer">
            @include('front.auth._partials.programs' , ['campus' => (null !== old('campus') ) ? old('campus') : $campus  ])
        </div>


        <?php else: ?>

            <input type="hidden" name="program" value="<?=$params['program']?>" />

        <?php endif; ?>



        @include('front.auth._partials.country')

        @if(isset($customFields) && count($customFields))
            @include('front.auth._partials.customFields' , [
                'customFields' => $customFields
            ])
        @endif
        @if (isset($settings['auth']['parent_login']) && $settings['auth']['parent_login'] =='Yes')
            <div class="form-group row">
                <div class="col-12 ">
                    <select name="role" class="form-control-lg form-control">
                        <option value="parent">{{__("I'm a Parent")}}</option>
                        <option value="parent">{{__("I'm an Agent")}}</option>
                    <!-- <option value="student">{{__("I'm a Student")}}</option> -->
                    </select>
            <div class="form-group row">
                <div class="col-12 ">
                    <input id="password" type="password"
                            class="form-control-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            name="password"
                            placeholder="{{__('Create Password')}}" required>
                    @if ($errors->has('role'))
                        <span class="invalid-feedback">
                        <strong>
                            {{ __($errors->first('role')) }}
                        </strong>
                    </span>
                    @endif
                </div>
            </div>

        @else
            <input type="hidden" name="role" value="student"/>
        @endif

        <div class="form-group row">
            <div class="col-12 ">
                <input id="password" type="password"
                        class="form-control-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                        name="password" placeholder="{{__('Password')}}"
                        required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>
                            {{ __($errors->first('password')) }}
                            </strong>
                        </span>
                    @endif
            </div>
        </div>

            <div class="form-group row">
                <div class="col-12 ">

                    <input id="password-confirm" type="password"
                            class="form-control-lg form-control"
                            name="password_confirmation"
                            placeholder="{{__('Confirm Password')}}"
                            required>

                </div>
            </div>

            @if (isset($settings['school']['terms']) && !empty($settings['school']['terms']))
                <div class="form-group row">
                    <div class="col-md-12 ">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                    class="custom-control-input"
                                    id="customCheck1">
                                <label class="custom-control-label"
                                    for="customCheck1">{{__('I agree to all ')}}
                                    <a href="javascript:void(0)"
                                        class="text-link">{{__('Terms')}}</a>
                                </label>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($settings['auth']['enable_recaptcha']) && $settings['auth']['enable_recaptcha'] == "Yes" && !empty($settings['auth']['recaptcha_site_key']))
                <div class="g-recaptcha" data-sitekey="{{$settings['auth']['recaptcha_site_key']}}"></div>

                @if ($errors->has('g-recaptcha-response'))
                    <span class="invalid-feedback" style="display: block">
                        <strong>{{__('Please check the the captcha.')}}</strong>
                    </span>
                @endif
                <div style="display:block;margin-bottom:15px;"></div>
            @endif

            @if(isset($settings['auth']['terms_conditions']) && !empty($settings['auth']['terms_conditions']))
                <div class="col-sm-12 text-left p-b-10">
                    {{__('By signing up, you agree to our Terms of Service and Conditions.')}}
                </div>
            @endif

            <div class="form-group text-center ">
                <div class="col-xs-12 p-b-20 ">
                    <button class="btn btn-block btn-lg btn-school text-white"
                                    style="background-color: {{ isset($settings['branding']['main_color']) ? $settings['branding']['main_color'] : '#2a77a6'  }}"
                                    type="submit ">{{ __('Register') }}</button>
                        </div>
                    </div>
                    <div class="form-group m-b-0 m-t-10 ">
                        <div class="col-sm-12 text-center ">
                            {{__('Already have an account?')}} <a
                                    href="{{ route('school.login' , $params) }}"
                                    class="text-link m-l-5 "><b>{{ __('Sign in') }}</b></a>
                        </div>
                    </div>
                </div>
            </div>
    </form>
