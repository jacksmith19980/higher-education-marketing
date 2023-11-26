@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Applicant')}}</h4>
                        <hr>

                        <form method="POST" action="{{ route('students.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Create Applicant') }}"
                              data-add-button="{{__('Create Applicant') }}">
                            @csrf

                            <h6>{{__('Personal Information')}}</h6>
                            <section>

                                <div class="row">
                                    @if (!count($campuses))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any campuses, Please add campuse first') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                        @include('back.layouts.core.forms.hidden-input',
                                        [
                                            'name'      => 'role',
                                            'label'     => 'Role' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => 'student'
                                        ])
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'first_name',
                                            'label'     => 'First Name' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'last_name',
                                            'label'     => 'Last Name' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'email',
                                            'label'     => 'Email' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.phone',
                                        [
                                            'name'          => 'phone',
                                            'label'         => 'Phone' ,
                                            'class'         =>'' ,
                                            'required'      => false,
                                            'attr'          => '',
                                            'international' => true,
                                            'value'         => ''
                                        ])
                                    </div>

                                </div> <!-- row -->

                                <div class="row">

                                    @include('back.layouts.core.forms.campuses', [
                                        'class'     => '',
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => '',
                                        'data'      => $campuses
                                    ])
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.checkbox',
                                            [
                                            'name'            => 'send_email',
                                                'label'         => 'Share login credentials' ,
                                                'class'         => '' ,
                                                'required'      => false,
                                                'attr'          => '',
                                                'helper_text'   => 'Send the login credentials to the student',
                                                'value'         =>  0,
                                                'default'       =>  1,
                                                'helper'        => 'Send the Login credentials to the student'
                                            ])

                                    </div>


                                </div>

                            </section>

                            <h6>{{__('Address & Location')}}</h6>
                            <section>

                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'address',
                                            'label'     => 'Address' ,
                                            'class'     =>'' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'city',
                                            'label'     => 'City' ,
                                            'class'     =>'' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'postal_code',
                                            'label'     => 'Postal Code' ,
                                            'class'     =>'' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'country',
                                            'label'     => 'Country' ,
                                            'class'     =>'' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>


                                </div> <!-- row -->
                            </section>

                             <h6>{{__('Application')}}</h6>
                            <section>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            {{__('Select an application to submit')}}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'application',
                                            'label'     => 'Application' ,
                                            'class'     =>'select2' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
                                            'placeholder' => 'Select Application',
                                            'data'      => $applications->pluck('title' , 'id')->toArray(),
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
