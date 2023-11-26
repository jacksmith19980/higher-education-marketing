@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Edit Cohort')}} - {{$group->title}}</h4>
                        <hr>

                        <form method="POST" action="{{ route('groups.update' , $group) }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update Cohort') }}"
                              data-add-button="{{__('Save')}}">
                            @csrf
                            @method('PUT')
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

                                    @if (!count($students))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any student, Please add a student first') }}
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
                                            'value'     => isset($group->title) ? $group->title : ''
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
                                            'value'     => $group->campus->id,
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
                                            'value'     => isset($group->course) ? $group->course->id : '',
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
                                            'value'     => isset($group->program) ? $group->program->id : '',
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
                                            'value'         => isset($group->start_date) ? $group->start_date : '',
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
                                            'value'         => isset($group->end_date) ? $group->end_date : '',
                                            'data'          => ''
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6" onmouseenter="app.showAddSchedule(this);" ontouchstart="app.showAddSchedule(this);" data-attr="{{route('schedule.create', ['course' => '1'])}}">

                                    @include('back.layouts.core.forms.multi-select',
                                        [
                                            'name'          => 'schedules[]',
                                            'label'         => 'Schedule' ,
                                            'class'         => 'date_schedule' ,
                                            'required'      => true,
                                            'attr'          => '',
                                            'value'         => $schedules,
                                            'data'          => SchoolHelper::getSchedulesList(),
                                        ])
                                    </div>
                                </div>
                            </section>

                            <h6>Select Students</h6>
                            <section>
                                @include('back.groups._partials.students.edit')
                            </section>

{{--                            <h6>Semesters</h6>--}}
{{--                            <section>--}}
{{--                                @include('back.groups._partials.semester.index')--}}
{{--                            </section>--}}

{{--                            <h6>Select Instructor</h6>--}}
{{--                            <section>--}}
{{--                                @include('back.groups._partials.instructor.edit')--}}
{{--                            </section>--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
