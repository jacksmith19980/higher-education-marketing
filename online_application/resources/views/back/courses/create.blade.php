@extends('back.layouts.default')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Course')}}</h4>
                        <hr>

                        <form method="POST" action="{{ route('courses.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Create Course') }}"
                              data-add-button="{{__('Save')}}">
                        @csrf

                            <!-- Step 1 -->
                            <h6>{{__('Course Information')}}</h6>
                            <section>
                                <div class="row">
                                    @if (!count($campuses))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any campuses, Please add campus first') }}
                                            <a href="{{route( 'campuses.create')}}">Click here to Create a Campus</a>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'      => 'title',
                                            'label'     => 'Course Title' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'slug',
                                            'label' => 'Course Code' ,
                                            'class' =>'' ,
                                            'required' => true,
                                            'attr' => '',
                                            'value' =>
                                            ''
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
                                        @include('back.layouts.core.forms.select', [
                                            'name'        => 'program',
                                            'label'       => 'Program' ,
                                            'class'       =>'select2 programs' ,
                                            'required'    => false,
                                            'attr'        => '',
                                            'value'       => '',
                                            'placeholder' => '',
                                            'data'        => $programs
                                        ])
                                    </div>
                                </div> <!-- row -->

                                 <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'      => 'properties[weighting]',
                                            'label'     => 'Weighting' ,
                                            'class'     => '',
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => ''
                                        ])
                                    </div>
                                    {{-- Hidden for future use --}}
                                    <div class="col-md-6" style="display: none">
                                        @include('back.layouts.core.forms.select', [
                                            'name'        => 'status',
                                            'label'       => 'Status' ,
                                            'class'       =>'select2' ,
                                            'required'    => false,
                                            'attr'        => '',
                                            'value'       => '',
                                            'placeholder' => '',
                                            'data'        => [
                                                'Completed' => 'Completed',
                                                'Scheduled' => 'Scheduled',
                                                'Skipped'   => 'Skipped',
                                                'Postponed' => 'Postponed',
                                            ]
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row hide">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'          => 'properties[course_registeration_fee]',
                                            'label'         => 'Registration Fee' ,
                                            'class'         =>'' ,
                                            'required'      => false,
                                            'attr'          => '',
                                            'value'         => '',
                                            'hint_after'    => isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : 'CAD',
                                            'helper'        => 'Leave blank to use global Registration Fee'
                                        ])

                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'          => 'properties[course_materials_fee]',
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
                                <!-- Step 2 -->
                                <h6>{{__('Course Virtual Assistant')}}</h6>
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
                                                'name'     => 'properties[featured_image]',
                                                'label'    => 'Featured Image' ,
                                                'class'    => '',
                                                'required' => false,
                                                'attr'     => '',
                                                'value'    => '',
                                            ])
                                        </div>
                                    </div>

                                    <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input', [
                                    'name' => 'properties[detail_link]',
                                    'label' => 'Detail Link' ,
                                    'class' => '' ,
                                    'value' => '',
                                    'required' => false,
                                    'attr' => ''
                                ])
                                    </div>
                                </div> <!-- row -->
                                </section>
                            @endfeatures

                            <!-- Step 3 -->
                                @features(['virtual_assistant', 'quote_builder', 'sis'])
                                    <h6>Dates/Prices</h6>
                                @nofeatures
                                    <h6>Dates</h6>
                                @endfeatures
                                <section>
                                    @include('back.courses._partials.program-dates')
                                </section>

{{--                            <!-- Step 4 -->--}}
{{--                            <h6>{{__('Module')}}</h6>--}}
{{--                            <section>--}}
{{--                                <div class="row">--}}

{{--                                    <div class="col-md-12">--}}
{{--                                        @include('back.layouts.core.forms.text-input',--}}
{{--                                        [--}}
{{--                                            'name'      => 'module_title[]',--}}
{{--                                            'label'     => 'Module Title' ,--}}
{{--                                            'class'     => '',--}}
{{--                                            'required'  => false,--}}
{{--                                            'attr'      => '',--}}
{{--                                            'value'     => ''--}}
{{--                                        ])--}}
{{--                                    </div>--}}

{{--                                </div> <!-- row -->--}}
{{--                            </section>--}}

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
