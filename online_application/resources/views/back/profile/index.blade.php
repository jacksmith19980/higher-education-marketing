@extends('back.layouts.default')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="float-left">
                    <h4 class="page-title">{{__('Account Settings')}}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{-- <div class="col-md-4"></div> --}}
                    <div class="col-md-8 offset-md-2">

                        <form method="POST" action="{{route('user.profile')}}" class="m-t-40"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="userId" value="{{$user->id}}"/>

                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'name',
                                    'label' => 'Name' ,
                                    'class' =>'' ,
                                    'required' => true,
                                    'attr' => '',
                                    'value' => $user->name
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.email-input',
                                    [
                                    'name' => 'email',
                                    'label' => 'Email' ,
                                    'class' =>'' ,
                                    'required' => true,
                                    'attr' => '',
                                    'value' => $user->email
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'phone',
                                    'label' => 'Phone' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' => $user->phone
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'position',
                                    'label' => 'Position' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' => $user->position
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.select', [
                                    'name' => 'language',
                                    'label' => 'Language',
                                    'class' => '',
                                    'required' => false,
                                    'attr' => '',
                                    'value' => isset($user->language) ? $user->language  : '',
                                    'placeholder' => 'Use the school\'s default language',
                                    'data' => SchoolHelper::getDefaultLanguages(),
                                    'helper' => 'Leave Empty to use the school\'s default language'
                                    ])
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.password',
                                    [
                                    'name' => 'password',
                                    'label' => 'Password' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' => '',
                                    'helper' => 'Leave blank to keep your current password'
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.password',
                                    [
                                    'name' => 'password_confirmation',
                                    'label' => 'Confirm Password' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' => '',
                                    'helper' => 'At least one letter, uppercase, number, symbol and a minimum of 8
                                    characters'
                                    ])
                                </div>
                                <div class="col-md-12">
                                    <ul class="list-unstyled">
                                        <li class="media">

                                            @if (isset($user->signature))
                                                <img class="d-flex m-r-15"
                                                    src="{{ Storage::disk('s3')->temporaryUrl($user->signature, \Carbon\Carbon::now()->addMinutes(5)) }}"
                                                    alt="Generic placeholder image" width="120">
                                            @endif

                                            <div class="media-body">
                                                <div class="form-group">
                                                    @include('back.layouts.core.forms.file-input', [
                                                        'name' => 'signature',
                                                        'label' => 'Signature' ,
                                                        'class' => '',
                                                        'required' => false,
                                                        'attr'      => '',
                                                        'value'     => '',
                                                    ])
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                {{--
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.html', [
                                        'name' => 'signature',
                                        'label' => 'Signature' ,
                                        'class' => '' ,
                                        'required' => false,
                                        'attr' => '',
                                        'value' => isset($user->signature) ? $user->signature  : '',
                                        ])
                                    </div>
                                --}}
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
@endsection
