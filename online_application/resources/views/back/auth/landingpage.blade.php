@extends('back.layouts.landingpage2')
@section('content')

<form class="siderbar-from" method="POST" action="{{route('landing.payment')}}">
    @csrf

    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <input placeholder="{{ __('First Name *') }}" id="name" type="text"
                    class="form-control-lg form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                    value="{{ old('name') }}" required>

                @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <input placeholder="{{ __('Last Name *') }}" id="lastname" type="text"
                    class="form-control-lg form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="lastname"
                    value="{{ old('lastname') }}" required>

                @if ($errors->has('lastname'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('lastname') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <select placeholder="{{ __('Country *') }}" id="country"
                    class="form-control-lg form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="country"
                    value="{{ old('country') }}" required>
                    <option value="">Country *</option>
                    @php

                    $data = \App\Helpers\Application\ApplicationHelpers::getCoutriesList();

                    @endphp

                    @foreach($data as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>

                @if ($errors->has('country'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('country') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <input id="email" type="email"
                    class="form-control-lg form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                    value="{{ old('email') }}" placeholder="{{__('Email Address *')}}" required>

                @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <input placeholder="{{ __('Phone *') }}" id="phone" type="text"
                    class="form-control-lg form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone"
                    value="{{ old('phone') }}" required>

                @if ($errors->has('phone'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <input placeholder="{{ __('Job Title *') }}" id="job_title" type="text"
                    class="form-control-lg form-control{{ $errors->has('job_title') ? ' is-invalid' : '' }}"
                    name="job_title" value="{{ old('job_title') }}" required>

                @if ($errors->has('job_title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('job_title') }}</strong>
                </span>
                @endif

            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <input placeholder="{{ __('School Name *') }}" id="institutionname" type="text"
                    class="form-control-lg form-control{{ $errors->has('institutionname') ? ' is-invalid' : '' }}"
                    name="institutionname" value="{{ old('institutionname') }}" required>

                @if ($errors->has('institutionname'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('institutionname') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <select placeholder="{{ __('School Type *') }}" id="school_type"
                    class="form-control-lg form-control{{ $errors->has('school_type') ? ' is-invalid' : '' }}"
                    name="school_type" value="{{ old('school_type') }}" required>
                    <option value="">School Type *</option>
                    <option value="Business School">Business School</option>
                    <option value="Career College">Career College</option>
                    <option value="College">College</option>
                    <option value="Community College">Community College</option>
                    <option value="K-12">K-12</option>
                    <option value="Language School">Language School</option>
                    <option value="Sixth form">Sixth form</option>
                    <option value="Summer Camps">Summer Camps</option>
                    <option value="University">University</option>
                    <option value="University/ESL">University/ESL</option>
                    <option value="Association">Association</option>
                    <option value="Digital Marketing Agency">Digital Marketing Agency</option>
                    <option value="Gov">Gov</option>
                    <option value="International Recruitment Agency">International Recruitment Agency
                    </option>
                    <option value="Other">Other</option>
                </select>

                @if ($errors->has('school_type'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('school_type') }}</strong>
                </span>
                @endif
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <input placeholder="{{ __('School Web Site') }} *" id="school_url" type="text"
                    class="form-control-lg form-control{{ $errors->has('school_url') ? ' is-invalid' : '' }}"
                    name="school_url" value="{{ old('school_url') }}" required>

                @if ($errors->has('school_url'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('school_url') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>

    <input type="hidden" name="plan" value="1">
    <!---Free Trial --->

    {{-- <div class="row">--}}
        {{-- <div class="col-md-12 col-sm-12 col-xs-12">--}}
            {{-- <div class="form-group">--}}
                {{-- <select placeholder="{{ __('Plan *') }}" id="plan" --}} {{--
                    class="form-control-lg form-control{{ $errors->has('plan') ? ' is-invalid' : '' }}" --}} {{--
                    name="plan" value="{{ old('plan') }}" required>--}}
                    {{-- <option>{{ __('Plan *') }}</option>--}}
                    {{-- @foreach($plans as $plan)--}}
                    {{-- <option value="{{$plan->id}}">{{$plan->title}}</option>--}}
                    {{-- @endforeach--}}
                    {{-- </select>--}}

                {{-- @if ($errors->has('plan'))--}}
                {{-- <span class="invalid-feedback">--}}
                    {{-- <strong>{{ $errors->first('plan') }}</strong>--}}
                    {{-- </span>--}}
                {{-- @endif--}}
                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </div>--}}

    <div class="form-group row">
        <div class="col-12 ">
            <div id="Password-Auth">
				<input id="password" type="password"
					class="form-control-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
					placeholder="{{__('Create Password *')}}" required>
			</div>
            @if ($errors->has('password'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <div class="col-12 ">

            <input id="password-confirm" type="password" class="form-control-lg form-control"
                name="password_confirmation" placeholder="{{__('Confirm Password *')}}" required>

        </div>
    </div>
    <div class="col-12">
        <p>HEM is committed to protecting and respecting your privacy, and we’ll only use your personal
            information to administer your account and to provide the products and services you
            requested from us. From time to time, we would like to contact you about our products and
            services, as well as other content that may be of interest to you. If you consent to us
            contacting you for this purpose, please tick below to say how you would like us to contact
            you:</p>
    </div>

    <!-- <div class="form-group row">
            <div class="col-md-12 ">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1" required="required"> -->

    <!--        </div>
                            </div>
                        </div> -->

    <div class="form-group row">
        <div class="col-md-12 ">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck2">
                <label class="custom-control-label" for="customCheck2">I agree to receive other communications from HEM
                </label>
            </div>
        </div>
    </div>

    <div class="col-12">
        <p>You can unsubscribe from these communications at any time. For more information on how to
            unsubscribe, our privacy practices, and how we are committed to protecting and respecting
            your privacy, please review our Privacy Policy.
            By clicking submit below, you consent to allow HEM to store and process the personal
            information submitted above to provide you the content requested.</p>
    </div>

    <div class="form-group text-center ">
        <div class="col-xs-12 p-b-20 ">
            <button class="btn btn-block btn-lg btn-info " type="submit"><span>Start my free 14-day trial</span><svg
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0"
                    viewBox="0 0 268.832 268.832" style="enable-background:new 0 0 512 512" xml:space="preserve">
                    <g>
                        <g xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M265.171,125.577l-80-80c-4.881-4.881-12.797-4.881-17.678,0c-4.882,4.882-4.882,12.796,0,17.678l58.661,58.661H12.5   c-6.903,0-12.5,5.597-12.5,12.5c0,6.902,5.597,12.5,12.5,12.5h213.654l-58.659,58.661c-4.882,4.882-4.882,12.796,0,17.678   c2.44,2.439,5.64,3.661,8.839,3.661s6.398-1.222,8.839-3.661l79.998-80C270.053,138.373,270.053,130.459,265.171,125.577z"
                                fill="#ffffff" data-original="#000000" style="" />
                        </g>
                </svg>
            </button>
        </div>
    </div>
</form>

@endsection
