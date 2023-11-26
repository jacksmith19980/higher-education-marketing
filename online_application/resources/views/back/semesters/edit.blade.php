@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Update Semester')}}</h4>
                        <hr>
                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('semesters.update', $semester) }}" class="validation-wizard wizard-circle m-t-40"
                                aria-label="{{ __('Update Semester') }}" data-add-button="{{__('Update Semester')}}"  enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
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
                                            'value'     => isset($semester->title) ? $semester->title : ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.date-input',
                                        [
                                            'name'          => 'start_date',
                                            'label'         => 'Start date',
                                            'class'         => '' ,
                                            'required'      => false,
                                            'attr'          => 'autocomplete=off',
                                            'value'         => isset($semester->start_date) ? $semester->start_date : '',
                                            'data'          => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.date-input',
                                            [
                                                'name'          => 'end_date',
                                                'label'         => 'End date',
                                                'class'         => '' ,
                                                'required'      => false,
                                                'attr'          => 'autocomplete=off',
                                                'value'         => isset($semester->end_date) ? $semester->end_date : '',
                                                'data'          => ''
                                        ])
                                    </div>
                                </div> <!-- row -->
                            </section>

                            <h6>{{__('Semester Programs/courses')}}</h6>
                            <section>
                                <div class="pl-2 row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.checkbox',
                                        [
                                            'name'          => 'programs_check',
                                            'label'         => false ,
                                            'class'         => '' ,
                                            'required'      => false,
                                            'attr'          => 'onchange=app.enablePrograms(this)',
                                            'helper_text'   => 'Related with Programs',
                                            'value'         =>  count($programs_selected) < 1 ? 0 : 1,
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
                                            'helper_text'   => 'Related with Courses',
                                            'value'         =>  count($courses_selected) < 1 ? 0 : 1,
                                            'default'       =>  1,
                                            'helper'        => ''
                                        ])
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="ml-2 col-md-6 program_wrapper" @if (count($programs_selected) < 1 ) style="display: none" @endif>
                                        @include('back.layouts.core.forms.multi-select',
                                        [
                                            'name'          => 'program[]',
                                            'label'         => 'Program' ,
                                            'class'         => 'select2',
                                            'required'      => false,
                                            'attr'          => 'onchange=app.groupByProgram(this)',
                                            'value'         => $programs_selected,
                                            'placeholder'   => 'Select a Program',
                                            'data'          => $programs
                                        ])
                                    </div>

                                    <div class="col-md-6 course_wrapper" @if (count($courses_selected) < 1 ) style="display: none" @endif>
                                        @include('back.layouts.core.forms.multi-select',
                                        [
                                            'name'          => 'course[]',
                                            'label'         => 'Courses' ,
                                            'class'         => 'select2',
                                            'required'      => false,
                                            'attr'          => 'onchange=app.groupByCourse(this)',
                                            'value'         => $courses_selected,
                                            'placeholder'   => 'Select a Program',
                                            'data'          => $courses
                                        ])
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="col-md-12">
                                            @include('back.layouts.core.forms.duallistbox',
                                            [
                                                'name'      => 'groups[]',
                                                'label'     => 'Groups' ,
                                                'class'     => 'SemseterGroupsSelection' ,
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => $groups_selected,
                                                'data'      => $groups_of_selected_program,
                                            ])
                                            </div>
                                        </div>
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
