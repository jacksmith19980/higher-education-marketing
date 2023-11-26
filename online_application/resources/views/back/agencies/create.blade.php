@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Add Recruiter Hub')}}</h4>
                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->


                    <form method="POST" action="{{ route('agencies.store') }}"
                        class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Create Recruiter Hub') }}"
                        data-add-button="{{__('Add Agency')}}">



                        @csrf

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
                                    'value' => ''
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
                                    'value' => ''
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
                                    'value' => ''
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
                                    'value' => ''
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
                                    'value' => ''
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
                                    'value' => ''
                                    ])
                                </div>
                            </div> <!-- row -->

                        </section>







                        <!-- Step 2 -->
                        <h6>Agents</h6>
                        <section>
                            @include('back.agencies._partials.agency-invite-agents')

                        </section>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
