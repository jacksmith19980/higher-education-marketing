@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add User')}}</h4>
                        <hr>
                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('users.store') }}" class="validation-wizard wizard-circle m-t-40"
                            aria-label="{{ __('Create user') }}" data-add-button="{{__('Add user')}}"  enctype="multipart/form-data">
                        @csrf
                        <!-- Step 1 -->
                            <h6>{{("User's information")}}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'name',
                                            'label'     => 'Name' ,
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div id="useremail" class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'email',
                                            'label'     => 'Email' ,
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                            'validator_url' => "users.check.exists"
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'phone',
                                            'label'     => 'Phone' ,
                                            'class'     => '',
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>
                                    @include('back.layouts.core.forms.campuses', [
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => '',
                                        'data'      => $campuses
                                    ])
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'position',
                                            'label'     => 'Position' ,
                                            'class'     => '',
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>


                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'role',
                                            'label'     => 'Role' ,
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => $roles
                                        ])
                                    </div>


                                </div> <!-- row -->
                                {{--  <div class="row"> <!-- row -->
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.password',
                                        [
                                            'name'      => 'password',
                                            'label'     => 'Password' ,
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.password',
                                        [
                                            'name'      => 'password_confirmation',
                                            'label'     => 'Confirm Password' ,
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                        ])
                                    </div>
                                </div>  --}} <!-- row -->
                            </section>




                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
