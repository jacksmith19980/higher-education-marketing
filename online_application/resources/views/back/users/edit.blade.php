@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Edit User')}}</h4>
                        <hr>
                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('users.update' , $user) }}" class="validation-wizard wizard-circle m-t-40"
                              aria-label="{{ __('Edit user') }}" data-add-button="{{__('Edit user')}}"  enctype="multipart/form-data">
                        @csrf
                            <input type="hidden" value="{{$user->id}}" name="userId">
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
                                            'value'     => $user->name
                                        ])
                                    </div>



                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'email',
                                            'label'     => 'Email' ,
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => $user->email
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
                                            'value'     => $user->phone
                                        ])
                                    </div>
                                    @include('back.layouts.core.forms.campuses', [
                                        'class'     => '',
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => !empty($user->campuses) ? array_column($user->campuses , 'id') : '',
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
                                            'value'     => $user->position
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.multi-select',
                                        [
                                            'name'              => 'role',
                                            'label'             => 'Role' ,
                                            'class'             => '',
                                            'required'          => true,
                                            'attr'              => '',
                                            'value'             => $user->roles->count() ? $user->roles->pluck('id')->toArray(): null,
                                            'placeholder'       => 'Select Role',
                                            'data'              => $roles
                                        ])
                                    </div>
                                </div> <!-- row -->
                                <div class="row"> <!-- row -->
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.password',
                                        [
                                            'name'      => 'password',
                                            'label'     => 'Password' ,
                                            'class'     => '',
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
                                            'helper'    => 'Leave empty to keep old password'
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.password',
                                        [
                                            'name'      => 'password_confirmation',
                                            'label'     => 'Confirm Password' ,
                                            'class'     => '',
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
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
