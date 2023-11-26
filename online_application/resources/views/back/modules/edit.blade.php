@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Edit Module')}} - {{$module->title}}</h4>

                        <form method="POST" action="{{ route('modules.update' , $module) }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update Module') }}"
                              data-add-button="{{__('Update Module')}}">
                            @csrf
                            @method('PUT')
                            <!-- Step 1 -->

                                <h6>{{__('Module Information')}}</h6>

                                <section>
                                    <div class="row">
                                        @if (!count($courses))
                                            <div class="alert alert-danger col-12">
                                                {{ __('You don\'t have any course, Please add course first') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            @include('back.layouts.core.forms.text-input',
                                            [
                                                'name'      => 'title',
                                                'label'     => 'Module Title' ,
                                                'class'     =>'' ,
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => $module->title
                                            ])
                                        </div>

                                    </div> <!-- row -->



                                    <div class="row">
                                        <div class="col-md-6">
                                            @include('back.layouts.core.forms.select',
                                            [
                                                'name'      => 'course',
                                                'label'     => 'Course' ,
                                                'class'     =>'select2' ,
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => $module->course->id,
                                                'data'      => $courses
                                            ])
                                        </div>
                                    </div> <!-- row -->
                                </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection