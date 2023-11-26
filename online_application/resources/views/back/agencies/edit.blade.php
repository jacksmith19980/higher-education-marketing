@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Update Recruiter Hub')}}</h4>
                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->


                    <form method="POST" action="{{ route('agencies.update' , $agency)  }}"
                        class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update Recruiter Hub') }}"
                        data-add-button="{{__('Update Recruiter Hub')}}">
                        @csrf
                        @method('PUT')

                        <!-- Step 1 -->
                        <h6>{{__('Recruiter Hub Information')}}</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'name',
                                    'label' => 'Name' ,
                                    'class' =>'' ,
                                    'required' => true,
                                    'attr' => '',
                                    'value' => isset($agency->name) ? $agency->name : ''
                                    ])
                                </div>

                                <div class="col-md-6">

                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'phone',
                                    'label' => 'Phone Number' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' => isset($agency->phone) ? $agency->phone : ''
                                    ])
                                </div>


                                <div class="col-md-6">

                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'email',
                                    'label' => 'Email Address',
                                    'required' => true,
                                    'class' =>'' ,
                                    'attr' => '',
                                    'value' => isset($agency->email) ? $agency->email : ''
                                    ])

                                </div>


                                <div class="col-md-6">

                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'country',
                                    'label' => 'Country' ,
                                    'required' => false,
                                    'class' =>'' ,
                                    'attr' => '',
                                    'value' => isset($agency->country) ? $agency->country : ''
                                    ])
                                </div>


                                <div class="col-md-6">

                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'city',
                                    'label' => 'City' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' => isset($agency->city) ? $agency->city : ''
                                    ])

                                </div>


                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-area',
                                    [
                                    'name' => 'description',
                                    'label' => 'Description' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' => isset($agency->description) ? $agency->description : ''
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
