@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Classroom')}}</h4>
                        <hr>

                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('classrooms.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Create Classroom') }}"
                              data-add-button="{{__('Save')}}">
                            @csrf

                            <h6>Classroom Information</h6>
                            <section>

                                <div class="row">
                                    @if (!count($campuses))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any campuses, Please add campuse first') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'title',
                                            'label'     => 'Classroom' ,
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
                                </div>

                                <hr>

                            @include('back.classrooms._partials.slots-in-repeater')

                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
