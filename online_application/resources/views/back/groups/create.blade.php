@extends('back.layouts.default')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Cohort')}}</h4>
                        <hr>

                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('groups.store') }}"
                              class="validation-wizard wizard-circle m-t-50" aria-label="{{ __('Create Cohort') }}"
                              data-add-button="{{__('Save')}}">
                            @csrf

                            <h6>{{__('Program Information')}}</h6>
                            <section>
                                <div class="row">

                                    @if (!count($campuses))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any campuses, Please add campuse first') }}
                                        </div>
                                    @endif

                                    @if (!count($courses))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any course, Please add a course first') }}
                                        </div>
                                    @endif

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'title',
                                            'label'     => 'Title' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'campus',
                                            'label'     => 'Campus' ,
                                            'class'     =>'select2' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => $campuses
                                        ])
                                    </div>

                                    <div class="col-md-6 hide">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'course',
                                            'label'     => 'Course' ,
                                            'class'     =>'select2' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => $courses
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'program',
                                            'label'     => 'Program' ,
                                            'class'     =>'select2' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                            'data'      => $programs
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.date-input', [
                                            'name'          => 'start_date',
                                            'label'         => 'Start date' ,
                                            'class'         => '' ,
                                            'required'      => true,
                                            'attr'          => 'autocomplete=off',
                                            'value'         => isset($startDate) ? $startDate : '',
                                            'data'          => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.date-input', [
                                            'name'          => 'end_date',
                                            'label'         => 'End date' ,
                                            'class'         => '' ,
                                            'required'      => true,
                                            'attr'          => 'autocomplete=off',
                                            'value'         => isset($endDate) ? $endDate : '',
                                            'data'          => ''
                                        ])
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-6" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create', ['course' => '1'])}}">

                                    @include('back.layouts.core.forms.multi-select',
                                        [
                                            'name'          =>  'schedules[]',
                                            'label'         => 'Schedule' ,
                                            'class'         => 'date_schedule' ,
                                            'required'      => true,
                                            'attr'          => '',
                                            'value'         => [],
                                            'data'          => SchoolHelper::getSchedulesList(),
                                        ])
                                    </div>
                                </div>
                            </section>

                            <h6>Select Students</h6>
                            <section>
                                @include('back.groups._partials.students.index')
                            </section>

{{--                            <h6>Semesters</h6>--}}
{{--                            <section>--}}
{{--                                @include('back.groups._partials.semester.index')--}}
{{--                            </section>--}}

{{--                            <h6>Select Instructor</h6>--}}
{{--                            <section>--}}
{{--                                @include('back.groups._partials.instructor.index')--}}
{{--                            </section>--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
