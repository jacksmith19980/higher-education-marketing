@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Edit Plan')}} - {{$plan->title}}</h4>
                    <hr>
                   
                    <form method="POST" action="{{ route('plans.update' , $plan) }}" class="validation-wizard wizard-circle m-t-40"
                          aria-label="{{ __('Update Plan') }}" data-add-button="{{__('Update Plan')}}"  enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Step 1 -->
                        <h6>Plan Information</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'title',
                                        'label'     => 'Title' ,
                                        'class'     =>'' ,
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => isset($plan->title) ? $plan->title : ''
                                    ])
                                </div>

                                <div class="col-md-3">
                                    @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'price',
                                            'label'     => 'Plan price' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => isset($plan->price) ? $plan->price : ''
                                    ])
                                </div>

                                <div class="col-md-9">
                                    @include('back.layouts.core.forms.multi-select', [
                                            'name'      => 'features[]',
                                            'label'     => 'Features',
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => isset($plan->features) ? $plan->features : [],
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
                                            'value'     => isset($plan->trial_period) ? $plan->trial_period : ''
                                    ])
                                </div>

                                <div class="w-100"></div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.checkbox', [
                                    'name' => 'is_active',
                                    'label' => false ,
                                    'class' => '' ,
                                    'required' => false,
                                    'attr' => '',
                                    'helper_text' => 'Active',
                                    'default' => 1,
                                    'value' => isset($plan->is_active) ? $plan->is_active : '',
                                    'helper' => 'Is the plan active'
                                    ])
                                </div>

                                <div class="w-100"></div>

                                <div class="col-md-3">
                                    @include('back.layouts.core.forms.text-input', [
                                            'name'      => 'properties[active_period]',
                                            'label'     => 'Active period (Days)',
                                            'class'     =>'' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
                                            'helper'    => 'Leave blank so plan does not expire',
                                            'value' => isset($plan->properties['active_period']) ? $plan->properties['active_period'] : ''
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
                                            'value'     => isset($plan->short_description) ? $plan->short_description : '',
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
                                            'value'     => isset($plan->features_description) ? $plan->features_description : '',
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
