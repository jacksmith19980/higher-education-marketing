@extends('front.layouts.minimal')
@section('content')

<div class="page-wrapper" style="padding-top: 100px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="float-left">
                            <h4 class="page-title">{{__('My Profile')}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{--  <div class="col-md-4"></div>  --}}
                            <div class="col-md-8 offset-md-2">

                                @if (session('success'))

                                    <div class="alert alert-success">
                                        {{session('success')}}
                                    </div>

                                @endif

                                <form method="POST" action="{{route('student.profile.update', $school)}}" class="m-t-40" enctype="multipart/form-data">
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
                                                    'value'     => $student->first_name
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
                                                    'value'     => $student->last_name
                                                ])
                                        </div>
                                        <div class="col-md-12">
                                            @include('back.layouts.core.forms.email-input',
                                                [
                                                'name'      => 'email',
                                                'label'     => 'Email' ,
                                                'class'     => '',
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => $student->email
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
                                        <div class="col-md-12">
                                            <h3>{{ __('Address')}}</h3>
                                            <hr/>
                                        </div>
                                        <div class="col-md-12">
                                            @include('back.layouts.core.forms.text-area',
                                                [
                                                    'name'      => 'address',
                                                    'label'     => 'Street' ,
                                                    'class'     =>'' ,
                                                    'required'  => false,
                                                    'attr'      => '',
                                                    'value'     => $student->address
                                                ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('back.layouts.core.forms.text-input',
                                                [
                                                    'name'      => 'city',
                                                    'label'     => 'City' ,
                                                    'class'     => '' ,
                                                    'required'  => false,
                                                    'attr'      => '',
                                                    'value'     => $student->city
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
                                                    'value'     => $student->postal_code
                                                ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('back.layouts.core.forms.select',
                                                [
                                                    'name'      => 'country',
                                                    'label'     => 'Country' ,
                                                    'class'     =>'' ,
                                                    'required'  => false,
                                                    'attr'      => '',
                                                    'value'     => $student->country,
                                                    'placeholder'   => 'Select Country',
                                                    'data'          => \App\Helpers\Application\FieldsHelper::getListData('mautic_countries')
                                                ])
                                        </div>
                                        <div class="col-md-1 offset-md-11">
                                            <input type="submit" value="Update" class="btn btn-success  m-t-10">
                                        </div>
                                    </div><!-- row -->
                                </form>
                            </div><!-- row -->
                        </div><!-- row -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
