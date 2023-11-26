@extends('back.layouts.default')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Edit Program')}} - {{$program->title}}</h4>
                        <hr>

                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->

                        <form method="POST" action="{{ route('programs.update' , $program) }}"
                                class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update program') }}"
                                data-add-button="{{__('Save')}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

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
                                            'attr'      => '',
                                            'value'     => isset($program->title) ? $program->title : ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'slug',
                                            'label' => 'Program Code' ,
                                            'class' =>'' ,
                                            'required' => true,
                                            'attr' => '',
                                            'value' => isset($program->slug) ? $program->slug : ''
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
                                        'value'     => isset($program->campuses) ? $program->campuses->pluck('id')->toArray() : [],
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
                                            'value'     => isset($program->courses) ? $program->courses->pluck('id')->toArray() : [],
                                            'data'      => $courses,
                                            'helper'    => 'Only courses without associated program are shown'
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'program_type',
                                            'label'     => 'Program Type' ,
                                            'class'     =>'select2' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => isset($program->program_type) ? $program->program_type : '',
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
                                            'value'         => isset($program->properties['program_registeration_fee']) ? $program->properties['program_registeration_fee'] : '',
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
                                            'value'         => isset($program->properties['program_materials_fee']) ? $program->properties['program_materials_fee'] : '',
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
                                            'value'    => isset($program->details) ? $program->details : ''
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
                                            'value'     => isset($program->properties['video']) ? $program->properties['video'] : ''
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="list-unstyled">
                                            <li class="media">

                                                @if (
                                                        isset($program->properties['featured_image']) &&
                                                        is_array($program->properties['featured_image']) &&
                                                        count($program->properties['featured_image']) > 0
                                                    )
                                                    <img class="d-flex m-r-15" src="{{ Storage::disk('s3')->temporaryUrl($program->properties['featured_image']['path'], \Carbon\Carbon::now()->addMinutes(5)) }}"
                                                         alt="Generic placeholder image" width="120">
                                                @endif
                                                    <div class="media-body">
                                                        <div class="form-group">
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
                                            </li>
                                        </ul>
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

                            @if (isset($customFields) && $customFields && count($customFields) > 0)
                                <!-- Step -->
                                <h6>Custom Fields</h6>
                                <section>
                                    @include('back.programs._partials.customfields.program-customfields', ['obj' => $program])
                                </section>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
