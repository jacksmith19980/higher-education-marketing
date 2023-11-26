@extends('back.layouts.landingpage2')
@section('content')

    <div>

        <div class="" style="text-align: center;">

            <h3>Fill in your details to create your <span style="color:#2a77a6">FREE TRIAL</span> account and be taken
                to your application.</h3>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-12">
                {{--                            <form class="form-horizontal m-t-20 landing-page-form" method="POST" action="https://application.crmforschools.net/register-landing">--}}
                <form class="form-horizontal m-t-20 landing-page-form" method="POST"
                      action="{{route('register-landing')}}">
                    @csrf
                    <input type="hidden" value="{{$name}}" name="name">
                    <input type="hidden" value="{{$lastname}}" name="lastname">
                    <input type="hidden" value="{{$email}}" name="email">
                    <input type="hidden" value="{{$password}}" name="password">
                    <input type="hidden" value="{{$institutionname}}" name="institutionname">
                    <input type="hidden" value="{{$school_type}}" name="school_type">
                    <input type="hidden" value="{{$school_url}}" name="school_url">
                    <input type="hidden" value="{{$plan}}" name="plan">

                    <input type='hidden' name='amount' value='{{$plan_obj->price}}'>
                    <input type='hidden' name='product' value='{{$plan_obj->title}}'>

                    <div class="row">
                        @if ($errors->has('card_error'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('card_error') }}</strong>
                            </span>
                        @endif
                        <div class="field_wrapper col-md-12 col-sm-12 col-xs-12">
                            @include('back.layouts.core.forms.text-input',
                            [
                                'name'      => 'card_no',
                                'label'     => 'Card Number' ,
                                'class'     => '',
                                'required'  => true,
                                'attr'      => '',
                                'value'     => ''
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="field_wrapper col-md-4 col-sm-6 col-xs-6">
                            @include('back.layouts.core.forms.text-input',
                            [
                                'name'      => 'ccExpiryMonth',
                                'label'     => 'Expiry Month (MM)' ,
                                'class'     => '',
                                'required'  => true,
                                'attr'      => '',
                                'placeholder' => '05',
                                'value'     => ''
                            ])
                        </div>

                        <div class="field_wrapper col-md-4 col-sm-6 col-xs-6">
                            @include('back.layouts.core.forms.text-input',
                            [
                                'name'      => 'ccExpiryYear',
                                'label'     => 'Expiry Year (YYYY)',
                                'class'     => '',
                                'required'  => true,
                                'attr'      => '',
                                'placeholder' => '2020',
                                'value'     => ''
                            ])
                        </div>

                        <div class="field_wrapper col-md-4 col-sm-6 col-xs-6">
                            @include('back.layouts.core.forms.text-input',
                            [
                                'name'      => 'cvvNumber',
                                'label'     => 'CVC' ,
                                'class'     => '',
                                'required'  => true,
                                'attr'      => '',
                                'placeholder' => '123',
                                'value'     => ''
                            ])
                        </div>
                    </div>
                    <div class="form-group text-center ">
                        <div class="col-xs-12 p-b-20 ">
                            <button class="btn btn-block btn-lg btn-info " type="submit "
                                    style="background-color:#fba819; border-color: #fba819; color: #333; text-transform: uppercase; font-weight: bold; ">{{ __('Pay') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection