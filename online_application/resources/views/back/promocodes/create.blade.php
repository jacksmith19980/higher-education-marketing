@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Promo Code')}}</h4>
                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('promocodes.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Create Promo Code') }}"
                              data-add-button="{{__('Add Promo Code')}}">
                        @csrf
                        <!-- Step 1 -->
                            <h6>{{ __('Create Promo Code') }}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select', [
                                            'name'      => 'type',
                                            'label'     => 'Type',
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => $types
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'      => 'reward',
                                            'label'     => 'Discount',
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
{{--                                    <div class="col-md-6">--}}
{{--                                        @include('back.layouts.core.forms.text-input', [--}}
{{--                                            'name'      => 'quantity',--}}
{{--                                            'label'     => 'Quantity',--}}
{{--                                            'class'     => '',--}}
{{--                                            'required'  => false,--}}
{{--                                            'attr'      => '',--}}
{{--                                            'value'     => ''--}}
{{--                                        ])--}}
{{--                                    </div>--}}
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'      => 'data[message]',
                                            'label'     => 'Message',
                                            'class'     =>'' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.date-input',
                                        [
                                            'name'      => 'commence_at',
                                            'label'     => 'Start Date',
                                            'class'     =>'' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => ''
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @include('back.layouts.core.forms.date-input',
                                            [
                                                'name'      => 'expires_at',
                                                'label'     => 'Expires At ',
                                                'class'     =>'' ,
                                                'required'  => false,
                                                'attr'      => '',
                                                'value'     => '',
                                                'data'      => ''
                                            ])
                                        </div>
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.multi-select', [
                                            'name'      => 'quotations[]',
                                            'label'     => 'Quotations' ,
                                            'class'     =>'select2' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => $quotations
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'          => 'code',
                                            'label'         => 'Code',
                                            'class'         => '',
                                            'required'      => false,
                                            'attr'          => '',
                                            'value'         => '',
                                            'placeholder'   => 'only letters numbers and -',
                                            'helper'        => 'Automatically generated if left empty'
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.checkbox', [
                                            'name'          => 'is_automatic',
                                            'label'         => '',
                                            'class'         => '' ,
                                            'required'      => false,
                                            'attr'          => '',
                                            'helper_text'   => 'Automatic',
                                            'value'         => '',
                                            'default'       => 1
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.checkbox', [
                                            'name'          => 'is_disposable',
                                            'label'         => '',
                                            'class'         => '' ,
                                            'required'      => false,
                                            'attr'          => '',
                                            'helper_text'   => 'Disposable',
                                            'value'         => '',
                                            'default'       => 1
                                        ])
                                    </div>
                                </div> <!-- row -->
                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection