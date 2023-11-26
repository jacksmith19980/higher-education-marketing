@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Add E-Signatures')}}</h4>
                    <hr>
                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                    <form method="POST" action="{{ route('envelope.store') }}"
                        class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Create E-Signatures') }}"
                        data-add-button="{{__('Create E-Signatures')}}" enctype="multipart/form-data">
                        @csrf
                        <!-- Step 1 -->
                        <h6>{{__('E-Signatures Information')}}</h6>
                        <section>

                            <div class="col-6">
                                @include('back.layouts.core.forms.hidden-input',
                                [
                                'name' => 'service',
                                'label' => "Service Name" ,
                                'class' => 'ajax-form-field' ,
                                'required' => true,
                                'attr' => '',
                                'value' => $serviceName
                                ])
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'title',
                                    'label' => 'Title' ,
                                    'class' => '',
                                    'required' => true,
                                    'attr' => '',
                                    'value' => ''
                                    ])
                                </div>

                                @include('back.layouts.core.forms.campuses', [
                                    'class'     => '',
                                    'required'  => true,
                                    'attr'      => '',
                                    'value'     => '',
                                    'data'      => $campuses
                                ])

                            </div> <!-- row -->
                            @include('back.layouts.core.forms.hidden-input',
                            [
                            'name' => 'properties[templates]',
                            'label' => 'Templates' ,
                            'class' => 'ajax-form-field' ,
                            'required' => true,
                            'attr' => '',
                            'value' => ''
                            ])
                            <div class="row mt-5">
                                <div class="text-right col-md-2 offset-10 m-b-10">
                                    <button type="button" class="btn btn-success"
                                        onclick="app.addTemplateToEnvelope('{{route('envelope.add.template')}}' , '' , '{{__('Add Document')}}' , this)">
                                        <i class="fa fa-plus"></i> {{__('Add Document')}}
                                    </button>
                                </div>

                                <div class="col-12">
                                <div class="temaplates-wrapper">
                                    <div class="alert alert-warning">
                                        {{__("You don't have any documents in this envelope")}}
                                    </div>
                                </div>
                                </div>

                            </div>



                @include('back.layouts.core.forms.hidden-input',
                [
                'name' => 'properties[signers]',
                'label' => 'Signers' ,
                'class' => 'ajax-form-field' ,
                'required' => true,
                'attr' => '',
                'value' => ''
                ])

                <div class="row mt-5">
                    <div class="text-right col-md-2 offset-10 m-b-10">
                        <button type="button" class="btn btn-success"
                            onclick="app.addSignerToEnvelope('{{route('envelope.add.signer')}}' , '' , '{{__('Add Signer')}}' , this)">
                            <i class="fa fa-plus"></i> {{__('Add Signer')}}
                        </button>
                    </div>
                </div>
                <div class="signers-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                {{__("You don't have any signers in this envelope")}}
                            </div>
                        </div>
                    </div>
                </div>
                </section>
                <!-- Step 2 -->
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
