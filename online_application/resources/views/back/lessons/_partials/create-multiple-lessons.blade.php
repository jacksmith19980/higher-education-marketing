@extends('back.layouts.default')

@section('content')

<div class="container-fluid">
<div class="row justify-content-center">
    <div class="col-md-10 col-offset-2">
        <div class="card hasShadow">
            <div class="card-body wizard-content">
                <h4 class="card-title">{{__('Add Lessons')}}</h4>
                <hr>
            <form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
                @csrf
                <div class="row">
                    <div class="col-6" id="semstersList">
                        @include('back.layouts.core.forms.select',
                            [
                                'name'          => 'semester',
                                'label'         => 'Semester' ,
                                'class'         => 'ajax-form-field  program-field',
                                'required'      => true,
                                'attr'          => "onChange=app.getMultipleLessons(this,'program',null)",
                                'data'          => $semesters,
                                'placeholder'   => 'Select a Semester',
                                'value'         => ''
                            ])
                    </div>

                    <div class="col-6" id="programsList">
                        @include('back.layouts.core.forms.select',
                            [
                                'name'          => 'program',
                                'label'         => 'Program' ,
                                'class'         => 'ajax-form-field  program-field',
                                'required'      => true,
                                'attr'          => "disabled",
                                'data'          => [],
                                'placeholder'   => 'Select a Program',
                                'value'         => ''
                        ])
                    </div>
                </div>
                <div id="groupsList">
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
