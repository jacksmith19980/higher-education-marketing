@extends('back.layouts.default')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Edit Course')}}</h4>
                        <hr>

                        <form method="POST" action="{{ route('courses.update' , $course) }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Edit Course') }}"
                              data-add-button="{{__('Update Course') }}">
                        @csrf
                        @method('PUT')

                           <!-- Step 1 -->
                            <h6>{{__('Course Information')}}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'title',
                                            'label' => 'Course Title' ,
                                            'class' =>'' ,
                                            'required' => true,
                                            'attr' => '',
                                            'value' => (isset($course->title)) ? $course->title : ''
                                        ])

                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'slug',
                                            'label' => 'Course Code' ,
                                            'class' =>'' ,
                                            'required' => true,
                                            'attr' => '',
                                            'value' => (isset($course->slug)) ? $course->slug : ''
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
                                        'value'     => isset($courseCampuses) ? $courseCampuses : [],
                                        'data'      => $campuses
                                    ])
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select', [
                                            'name'        => 'program',
                                            'label'       => 'Program' ,
                                            'class'       =>'select2' ,
                                            'required'    => false,
                                            'attr'        => '',
                                            'placeholder' => '-- Programs --',
                                            'value'       => isset($courseProgram) ? $courseProgram : '',
                                            'data'        => $programs
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'      => 'properties[weighting]',
                                            'label'     => 'Weighting' ,
                                            'class'     => '',
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => isset($course->properties['weighting']) ? $course->properties['weighting'] : '',
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
                                            'value'       => $course->status,
                                            'placeholder' => '',
                                            'data'        => [
                                                'Completed' => 'Completed',
                                                'Scheduled' => 'Scheduled',
                                                'Skipped'   => 'Skipped',
                                                'Postponed' => 'Postponed',
                                            ]
                                        ])
                                    </div>
                                </div>

                                <div class="row hide">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'properties[course_registeration_fee]',
                                            'label' => 'Registration Fee' ,
                                            'class' =>'' ,
                                            'required' => false,
                                            'attr' => '',
                                            'value' => isset($course->properties['course_registeration_fee']) ?
                                            $course->properties['course_registeration_fee'] : '' ,
                                            'hint_after' => isset($settings['school']['default_currency']) ?
                                            $settings['school']['default_currency'] : 'CAD',
                                        'helper' => 'Leave blank to use global Registration Fee'
                                        ])

                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'properties[course_materials_fee]',
                                            'label' => 'Materials Fee' ,
                                            'class' =>'' ,
                                            'required' => false,
                                            'attr' => '',
                                            'value' => isset($course->properties['course_materials_fee']) ?
                                            $course->properties['course_materials_fee'] : '' ,
                                            'hint_after' => isset($settings['school']['default_currency']) ?
                                            $settings['school']['default_currency'] : 'CAD',
                                            'helper' => 'Leave blank to use global Materials Fee'
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
                                            'value'    => isset($course->details) ? $course->details : ''
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="list-unstyled">
                                            <li class="media">

                                                @if (
                                                        isset($course->properties['featured_image']) &&
                                                        is_array($course->properties['featured_image']) &&
                                                        count($course->properties['featured_image']) > 0
                                                    )
                                                    <img class="d-flex m-r-15" src="{{ Storage::disk('s3')->temporaryUrl($course->properties['featured_image']['path'] , \Carbon\Carbon::now()->addMinutes(5)) }}"
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

                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.file-input', [
                                            'name'     => 'properties[featured_image]',
                                            'label'    => 'Featured Image' ,
                                            'class'    => '',
                                            'required' => false,
                                            'attr'     => '',
                                            'value'    => isset($course->properties['featured_image']) ? $course->properties['featured_image'] : ''
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'properties[detail_link]',
                                            'label' => 'Detail Link' ,
                                            'class' => '' ,
                                            'value' => isset($course->properties['detail_link']) ? $course->properties['detail_link'] : '',
                                            'required' => false,
                                            'attr' => ''
                                        ])
                                    </div>
                                </div> <!-- row -->
                            </section>
                            @endfeatures

                            <!-- Step 3 -->
                            <h6>{{__('Dates/Prices')}}</h6>
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
{{--                                            'class'     =>'' ,--}}
{{--                                            'required'  => false,--}}
{{--                                            'attr'      => '',--}}
{{--                                            'value'     => isset($course->modules[0]->title) ? $course->modules[0]->title : ''--}}
{{--                                        ])--}}
{{--                                    </div>--}}

{{--                                </div> <!-- row -->--}}
{{--                            </section>--}}

                            @if (isset($customFields) && $customFields && count($customFields) > 0)
                                <!-- Step -->
                                <h6>{{__('Custom Fields')}}</h6>
                                <section>
                                    @include('back.programs._partials.customfields.program-customfields', ['obj' => $course])
                                </section>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
