@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Add Campus')}}</h4>
                    <hr>
                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                    <form method="POST" action="{{ route('campuses.store') }}" class="validation-wizard wizard-circle m-t-40"
                          aria-label="{{ __('Create Campus') }}" data-add-button="{{__('Add Campus')}}"  enctype="multipart/form-data">
                        @csrf
                        <!-- Step 1 -->
                        <h6>Campus Information</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'title',
                                        'label'     => 'Campus' ,
                                        'class'     => '',
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'slug',
                                        'label'     => 'Code\Slug' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>
                            </div> <!-- row -->
                        </section>

                        <!-- Step 2 -->
                        <h6>Campus Details</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[address]',
                                        'label'     => 'Address' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[province]',
                                        'label'     => 'Province' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>
                            </div> <!-- row -->

                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[city]',
                                        'label'     => 'City' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'properties[zip]',
                                        'label'     => 'Postal Code' ,
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>
                            </div> <!-- row -->

                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.select',
                                    [
                                        'name'          => 'properties[country]',
                                        'label'         => 'Country' ,
                                        'class'         => 'campus_country',
                                        'required'      => false,
                                        'attr'          => '',
                                        'value'         => '',
                                        'placeholder'   => 'Select Country',
                                        'data'          => \App\Helpers\Application\FieldsHelper::getListData('mautic_countries')
                                    ])
                                </div>

                            </div> <!-- row -->
                        </section>

                        @features(['virtual_assistant'])
                            @include('back.campuses._partials.steps.virtual_assistant')
                        @endfeatures

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
