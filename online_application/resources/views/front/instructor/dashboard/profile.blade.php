@extends('front.layouts.new-instructors')

@section('content')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-top: 5px solid #004d6e;">
                    <div class="card-body">
                        <h4 class="page-title">{{__('My Profile')}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{session('success')}}
                                    </div>
                                @endif

                                <form method="POST" action="{{route('instructor.profile.update', $school)}}" class="m-t-40" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            @include('back.layouts.core.forms.text-input',
                                                [
                                                    'name'      => 'first_name',
                                                    'label'     => 'First Name' ,
                                                    'class'     =>'' ,
                                                    'required'  => true,
                                                    'attr'      => '',
                                                    'value'     => $instructor->first_name
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
                                                    'value'     => $instructor->last_name
                                                ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('back.layouts.core.forms.email-input',
                                                [
                                                'name'      => 'email',
                                                'label'     => 'Email' ,
                                                'class'     => '',
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => $instructor->email
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('back.layouts.core.forms.phone',
                                                [
                                                'name'      => 'phone',
                                                'label'     => 'Phone' ,
                                                'class'     => '',
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => $instructor->phone
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('back.layouts.core.forms.password',
                                                [
                                                'name'      => 'password',
                                                'label'     => 'Password' ,
                                                'class'     =>'' ,
                                                'required'  => false,
                                                'attr'      => '',
                                                'value'     => '',
                                                'helper'    => 'Leave blank to keep your current password'
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('back.layouts.core.forms.password',
                                                [
                                                'name'      => 'password_confirmation',
                                                'label'     => 'Confirm Password' ,
                                                'class'     =>'' ,
                                                'required'  => false,
                                                'attr'      => '',
                                                'value'     => '',
                                            ])
                                        </div>
                                        <div class="col-md-1 offset-md-11">
                                            <input type="submit" value="Update" class="btn btn-success  m-t-10">
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
