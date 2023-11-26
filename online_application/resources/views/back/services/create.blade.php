@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Educational Service')}}</h4>
                        <hr>
                        <form method="POST" action="{{ route('services.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Add New') }}"
                              data-add-button="{{__('Add New') }}">
                            @csrf
                            <h6>{{__('Information')}}</h6>
                            <section>

                                <div class="row">
                                    @if (!count($educationalServiceCategories))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any educational service category, Please add one first') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'service_name',
                                            'label'     => 'Name' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'service_code',
                                            'label'     => 'Code' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                            'validator_url' => "educationalservice.code-validator"
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="service_description">{{ __('Description') }}<span class="text-danger">*</span></label>
                                        <textarea name="service_description" class="form-control mb-2" id="service_description" required rows="4"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'amount',
                                            'label'     => 'Amount' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        <div onmouseenter="app.showAddSingleItem(this);" ontouchstart="app.showAddSingleItem(this);" id="{{$uid}}">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'          => 'category',
                                            'label'         => 'Category' ,
                                            'class'         => 'new-single-item' ,
                                            'required'      => true,
                                            'attr'          => '',
                                            'value'         => '',
                                            'data'         => $educationalServiceCategories,
                                            'addNewRoute'   => route('educationalservicecategories.store')
                                        ])
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
