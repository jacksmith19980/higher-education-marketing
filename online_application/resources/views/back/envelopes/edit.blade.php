@extends('back.layouts.default')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Edit Envelope')}}</h4>
                    <hr>
                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                    <form method="POST" action="{{ route('envelope.edit' , ['envelope' => $envelope]) }}"
                        class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Edit Envelope') }}"
                        data-add-button="{{__('Edit Envelope')}}" enctype="multipart/form-data">
                        @csrf
                        <!-- Step 1 -->
                        <h6>{{__('Envelope Information')}}</h6>
                        <section>

                            <div class="col-6">
                                @include('back.layouts.core.forms.hidden-input',
                                [
                                'name' => 'service',
                                'label' => "Service Name" ,
                                'class' => 'ajax-form-field' ,
                                'required' => true,
                                'attr' => '',
                                'value' => $envelope->service
                                ])
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'title',
                                    'label' => 'Title' ,
                                    'class' => '',
                                    'required' => true,
                                    'attr' => '',
                                    'value' => $envelope->title
                                    ])
                                </div>
                                @include('back.layouts.core.forms.campuses', [
                                    'class'     => '',
                                    'required'  => true,
                                    'attr'      => '',
                                    'value'     => isset($envelopeCampuses) ? $envelopeCampuses : [],
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
                            'value' => $envelope->properties['templates']
                            ])

                            @include('back.layouts.core.forms.hidden-input',
                            [
                            'name' => 'properties[signers]',
                            'label' => 'Templates' ,
                            'class' => 'ajax-form-field' ,
                            'required' => true,
                            'attr' => '',
                            'value' => isset($envelope->properties['signers'])?$envelope->properties['signers'] : []
                            ])
                            <div class="row">
                                <div class="text-right col-md-2 offset-10 m-b-20">
                                    <button type="button" class="btn btn-success"
                                        onclick="app.addTemplateToEnvelope('{{route('envelope.add.template')}}' , '' , '{{__('Add Template')}}' , this)">
                                        <i class="fa fa-plus"></i> {{__('Add Template')}}
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" name="envelopeID" value="{{$envelope->id}}" />

                            @include('back.envelopes._partials.template-rows', [
                            'envelopeTemplates' =>
                            isset($envelope->properties['templates']) ? json_decode($envelope->properties['templates'] ,
                            true) : [],
                            'envelope' => $envelope
                            ])




                            <div class="row">
                                <div class="text-right col-md-2 offset-10 m-b-20">
                                    <button type="button" class="btn btn-success"
                                        onclick="app.addSignerToEnvelope('{{route('envelope.add.signer')}}' , '' , '{{__('Add Signer')}}' , this)">
                                        <i class="fa fa-plus"></i> {{__('Add Signer')}}
                                    </button>
                                </div>
                            </div>
                            @include('back.envelopes._partials.signer-rows', [
                            'envelopeSigners' => isset($envelope->properties['signers']) ?
                            json_decode($envelope->properties['signers'] , true) : []
                            ])
                        </section>
                        <!-- Step 2 -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
