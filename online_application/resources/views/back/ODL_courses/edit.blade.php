@extends('back.layouts.default')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body wizard-content">

                    <h4 class="card-title">{{__('Add Course')}}</h4>

                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->

                    <form method="POST" action="{{ route('courses.update' , $course) }}" class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Edit Course') }}" data-add-button="{{__('Edit Course')}}">

                        @csrf

                        @method('PUT')

                        <!-- Step 1 -->

                        <h6>{{__('Course Information')}}</h6>

                         <section>

                            <div class="row">

                                <div class="col-md-12">

                                    @include('back.layouts.core.forms.text-input',

                                    [

                                        'name'      => 'title',

                                        'label'     => 'Course Title' ,

                                        'class'     =>'' ,

                                        'required'  => true,

                                        'attr'      => '',

                                        'value'     => (isset($course->title)) ? $course->title : ''

                                    ])

                                </div>

                            </div> <!-- row -->



                            <div class="row">



                                <div class="col-md-6">

                                    @include('back.layouts.core.forms.multi-select',

                                    [

                                        'name'      => 'campuses[]',

                                        'label'     => 'Campus' ,

                                        'class'     =>'select2' ,

                                        'required'  => false,

                                        'attr'      => '',

                                        'value'     => isset($courseCampuses) ? $courseCampuses : [],

                                        'data'      => $campuses

                                    ])

                                </div>



                                <div class="col-md-6">

                                    @include('back.layouts.core.forms.text-input',

                                    [

                                        'name'          => 'properties[course_registeration_fee]',

                                        'label'         => 'Registration Fee' ,

                                        'class'         =>'' ,

                                        'required'      => false,

                                        'attr'          => '',

                                        

                                        'value'         => isset($course->properties['course_registeration_fee']) ? $course->properties['course_registeration_fee'] : '' ,

                                        

                                        'hint_after'    => isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : 'CAD',

                                        'helper'        => 'Leave blank to use global Registration Fee'

                                       ])

                                </div>



                                <div class="col-md-6">

                                    @include('back.layouts.core.forms.text-input',

                                    [

                                        'name'          => 'properties[course_materials_fee]',

                                        'label'         => 'Materials Fee' ,

                                        'class'         =>'' ,

                                        'required'      => false,

                                        'attr'          => '',

                                        'value'         => isset($course->properties['course_materials_fee']) ? $course->properties['course_materials_fee'] : '' ,

                                        

                                        'hint_after'    => isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : 'CAD',

                                        'helper'        => 'Leave blank to use global Materials Fee'

                                       ])

                                </div>





                            </div> <!-- row -->





                        </section>



                        <!-- Step 2 -->

                        <h6>Pricing</h6>

                        <section>

                            @include('back.courses._partials.course-pricing')

                        </section>



                         <h6>Dates</h6>

                        <section>

                            @include('back.courses._partials.course-dates')

                        </section>



                        <h6>Addons</h6>

                        <section>

                            @include('back.courses._partials.course-addons')

                        </section>

                        

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

