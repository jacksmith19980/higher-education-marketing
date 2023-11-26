@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Plan')}}</h4>
                        <hr>

                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('plans.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Creat Plan') }}"
                              data-add-button="Add Plan">
                        @csrf

                        <!-- Step 1 -->
                            <h6>School Information</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input',
                                            [
                                                'name'      => 'title',
                                                'label'     => 'Plan title' ,
                                                'class'     =>'' ,
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-3">
                                        @include('back.layouts.core.forms.text-input', [
                                                'name'      => 'price',
                                                'label'     => 'Plan price' ,
                                                'class'     =>'' ,
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-9">
                                        @include('back.layouts.core.forms.multi-select', [
                                                'name'      => 'features[]',
                                                'label'     => 'Features',
                                                'class'     => '',
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => '',
                                                'data'      => \App\Helpers\Plan\PlanHelpers::getPlanFeatures()
                                        ])
                                    </div>

                                    <div class="col-md-3">
                                        @include('back.layouts.core.forms.text-input',
                                            [
                                                'name'      => 'trial_period',
                                                'label'     => 'Trial period (Days)',
                                                'class'     => '',
                                                'required'  => false,
                                                'attr'      => '',
                                                'value'     => ''
                                        ])
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-md-2">
                                        @include('back.layouts.core.forms.checkbox', [
                                        'name' => 'is_active',
                                        'label' => false ,
                                        'class' => '',
                                        'required' => false,
                                        'attr' => '',
                                        'helper_text' => 'Active',
                                        'value' => 1,
                                        'default' => 1,
                                        'helper' => 'Is the plan active'
                                        ])
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-md-3">
                                        @include('back.layouts.core.forms.text-input',
                                            [
                                                'name'      => 'properties[active_period]',
                                                'label'     => 'Active period (Days)',
                                                'class'     =>'' ,
                                                'required'  => false,
                                                'attr'      => '',
                                                'value'     => '',
                                                'helper' => 'Is the plan active'
                                        ])
                                    </div>

                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input',
                                            [
                                                'name'      => 'short_description',
                                                'label'     => 'Short Description' ,
                                                'class'     =>'' ,
                                                'required'  => false,
                                                'attr'      => '',
                                                'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-area',
                                            [
                                                'name'      => 'features_description',
                                                'label'     => 'Description' ,
                                                'class'     =>'' ,
                                                'required'  => false,
                                                'attr'      => '',
                                                'value'     => ''
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
