@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Add Semester')}}</h4>
                    <hr>
                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                    <form method="POST" action="{{ route('semesters.store') }}" class="validation-wizard wizard-circle m-t-40"
                          aria-label="{{ __('Create Semester') }}" data-add-button="{{__('Add Semester')}}"  enctype="multipart/form-data">
                        @csrf
                        <!-- Step 1 -->
                        <h6>{{__('Semester Information')}}</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'title',
                                        'label'     => 'Semester',
                                        'class'     => '',
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.date-input',
                                [
                                    'name'          => 'start_date',
                                    'label'         => 'Start date' ,
                                    'class'         => '' ,
                                    'required'      => false,
                                    'attr'          => 'autocomplete=off',
                                    'value'         => '',
                                    'data'          => ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.date-input',
                                [
                                    'name'          => 'end_date',
                                    'label'         => 'End date' ,
                                    'class'         => '' ,
                                    'required'      => false,
                                    'attr'          => 'autocomplete=off',
                                    'value'         => '',
                                    'data'          => ''
                                    ])
                                </div>
                            </div> <!-- row -->
                        </section>

                        <h6>{{__('Programs/Courses')}}</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.checkbox',
                                    [
                                        'name'          => 'programs_check',
                                        'label'         => false ,
                                        'class'         => '' ,
                                        'required'      => false,
                                        'attr'          => 'onchange=app.enablePrograms(this)',
                                        'helper_text'   => 'Related wit Programs',
                                        'value'         =>  0,
                                        'default'       =>  1,
                                        'helper'        => ''
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.checkbox',
                                    [
                                        'name'          => 'courses_check',
                                        'label'         => false ,
                                        'class'         => '' ,
                                        'required'      => false,
                                        'attr'          => 'onchange=app.enableCourses(this)',
                                        'helper_text'   => 'Related wit Courses',
                                        'value'         =>  0,
                                        'default'       =>  1,
                                        'helper'        => ''
                                    ])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 program_wrapper" style="display: none">
                                    @include('back.layouts.core.forms.multi-select',
                                    [
                                        'name'      => 'program[]',
                                        'label'     => 'Program' ,
                                        'class'     => 'select2',
                                        'required'  => false,
                                        'attr'      => 'onchange=app.groupByProgram(this)',
                                        'value'     => '',
                                        'placeholder' => 'Select a Program',
                                        'data'      => $programs
                                    ])
                                </div>

                                <div class="col-md-6 course_wrapper" style="display: none">
                                    @include('back.layouts.core.forms.multi-select',
                                    [
                                        'name'      => 'course[]',
                                        'label'     => 'Courses' ,
                                        'class'     => 'select2',
                                        'required'  => false,
                                        'attr'      => 'onchange=app.groupByCourse(this)',
                                        'value'     => '',
                                        'placeholder' => 'Select a Program',
                                        'data'      => $courses
                                    ])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="GroupsList">
                                    @include('back.semesters._partials.groups-list' , [
                                        'groups'  => []
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
