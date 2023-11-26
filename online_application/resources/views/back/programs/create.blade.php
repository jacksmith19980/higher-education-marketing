@extends('back.layouts.default')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Program')}}</h4>
                        <hr>

                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('programs.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Create program') }}"
                              data-add-button="{{__('Save')}}" enctype="multipart/form-data">
                            @csrf

                            <!-- Step -->
                            <h6>{{__('Program Information')}}</h6>

                            <section>
                                <div class="row">
                                    @if (!count($campuses))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any campuses, Please add campuse first') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'title',
                                            'label'     => 'Title' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => 'onblur=app.constructSlug(this)',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'slug',
                                            'label' => 'Program Code' ,
                                            'class' =>'' ,
                                            'required' => true,
                                            'attr' => 'onkeyup=app.validateFieldName(this)',
                                            'value' => ''
                                        ])
                                        @if ($errors->has('slug'))
                                            <span class="error">
                                                <strong>{{ $errors->first('slug') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    @include('back.layouts.core.forms.campuses', [
                                        'class'     => '',
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => '',
                                        'data'      => $campuses
                                    ])
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.multi-select',
                                        [
                                            'name'      => 'courses[]',
                                            'label'     => 'Courses' ,
                                            'class'     =>'select2' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => $courses,
                                            'helper'        => 'Only courses without associated program are shown'
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'program_type',
                                            'label'     => 'Program Type' ,
                                            'class'     =>'select2 program_type' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => ProgramHelpers::getProgramTypes()
                                        ])
                                    </div>
                                </div>

                                <div class="row hide">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'          => 'properties[program_registeration_fee]',
                                            'label'         => 'Registeration Fee' ,
                                            'class'         =>'' ,
                                            'required'      => false,
                                            'attr'          => '',
                                            'value'         => '',
                                            'hint_after'    => isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : 'CAD',
                                            'helper'        => 'Leave blank to use global Registeration Fee'
                                           ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'          => 'properties[program_materials_fee]',
                                            'label'         => 'Materials Fee' ,
                                            'class'         =>'' ,
                                            'required'      => false,
                                            'attr'          => '',
                                            'value'         => '',
                                            'hint_after'    => isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : 'CAD',
                                            'helper'        => 'Leave blank to use global Materials Fee'
                                           ])
                                    </div>

                                </div> <!-- row -->

                            </section>

                            @features(['virtual_assistant'])
                            <!-- Step -->
                            <h6>{{__('Program Virtual Assistant')}}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.html', [
                                            'name'     => 'details',
                                            'label'    => 'Details' ,
                                            'class'    => '' ,
                                            'required' => false,
                                            'attr'     => '',
                                            'value'    => ''
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'properties[video]',
                                            'label'     => 'Video' ,
                                            'class'     => '' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.file-input', [
                                            'name'     => 'featured_image',
                                            'label'    => 'Featured Image' ,
                                            'class'    => '',
                                            'required' => false,
                                            'attr'     => '',
                                            'value'    => '',
                                        ])
                                    </div>
                                </div>
                            </section>
                            @endfeatures
{{--                            <h6>Pricing</h6>--}}
{{--                            <section>--}}
{{--                                @include('back.programs._partials.program-pricing')--}}
{{--                            </section>--}}

                            <!-- Step -->
                            <h6>Dates/Prices</h6>
                            <section>
                                @include('back.programs._partials.program-dates')
                            </section>

                            @features(['quote_builder'])
                                <!-- Step -->
                                <h6>Quote Builder Add-ons</h6>
                                <section>
                                    @include('back.programs._partials.program-addons')
                                </section>
                            @endfeatures

                                @if ($customFields && count($customFields) > 0)
                                    <!-- Step -->
                                        <h6>Custom Fields</h6>
                                        <section>
                                            @include('back.programs._partials.customfields.program-customfields')
                                        </section>
                                @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
