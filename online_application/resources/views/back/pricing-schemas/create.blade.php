@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Pricing Schema')}}</h4>
                        <hr>
                        <form method="POST" action="{{ route('pricing.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Add New') }}"
                              data-add-button="{{__('Add New') }}">
                            @csrf
                            <h6>{{__('Information')}}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'title',
                                            'label'     => 'Title' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.switch',
                                        [
                                            'name' => 'active',
                                            'label' => __("Active"),
                                            'class' => 'switch ajax-form-field',
                                            'required' => true,
                                            'attr' => 'data-on-text=Yes data-off-text=No',
                                            'helper_text' => '',
                                            'value' => true,
                                            'default' => true
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="description">{{ __('Description') }}<span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control mb-2" id="description" required rows="4"></textarea>
                                    </div>
                                </div>

                                <div class="row" id="pricings_wrapper">
                                </div>
                                <input type="hidden" id="current_id" name="current_id" value="0">
                                <div class="row">
                                    <div class="col-md-2 offset-10 m-b-30">
                                        @include('back.layouts.core.helpers.add-elements-button' , [
                                            'text'          => 'Add Dates',
                                            'action'        => 'pricingSchema.getPricingRow',
                                            'container'     => '#pricings_wrapper'
                                        ])
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