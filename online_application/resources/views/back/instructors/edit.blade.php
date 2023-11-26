@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Edit Instructor')}} - {{$instructor->name}}</h4>
                        <hr>

                        <form method="POST" action="{{ route('instructors.update' , $instructor) }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update Instructor') }}"
                              data-add-button="{{__('Save')}}">
                            @csrf
                            @method('PUT')

                            <h6>Instructors Information</h6>
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
                                            'name'      => 'first_name',
                                            'label'     => 'First Name' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => $instructor->first_name
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'last_name',
                                            'label'     => 'Last Name' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => $instructor->last_name
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'email',
                                            'label'     => 'Email' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => $instructor->email
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'phone',
                                            'label'     => 'Phone' ,
                                            'class'     => '' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => $instructor->phone
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.multi-select',
                                        [
                                            'name'      => 'campuses[]',
                                            'label'     => 'Campus' ,
                                            'class'     => 'select2' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => \App\Helpers\School\CampusHelpers::getCampusesInArrayOnlyTitleId($instructor->campuses),
                                            'data'      => $campuses
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.multi-select', [
                                            'name'      => 'courses[]',
                                            'label'     => 'Courses' ,
                                            'class'     => 'select2' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => \App\Helpers\School\CourseHelpers::getCoursesInArrayOnlyTitleId($instructor->courses),
                                            'data'      => $courses,
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