
@extends('back.layouts.default')

@section('content')

<div class="container-fluid">
<div class="row justify-content-center">
    <div class="col-md-10 col-offset-2">
        <div class="card hasShadow">
            <div class="card-body wizard-content">
                <h4 class="card-title">{{__('Edit Lessons')}}</h4>
                <hr>
            <form method="POST" action="{{ route($route , ['lesson' => $lesson]) }}"
             class="validation-wizard wizard-circle m-t-40"
                aria-label="{{ __('Edit Lesson') }}"
                    data-add-button="{{__('Update Lesson')}}"
                >
                @csrf
                <div class="row">
                    <div class="col-6" id="semstersList">
                        @include('back.layouts.core.forms.select',
                            [
                                'name'          => 'semester',
                                'label'         => 'Semester' ,
                                'class'         => 'ajax-form-field  program-field',
                                'required'      => true,
                                'attr'          => "onChange=app.getMultipleLessons(this,'program',$lesson->id)",
                                'data'          => $semesters,
                                'placeholder'   => 'Select a Semester',
                                'value'         => $semester->id
                            ])
                    </div>

                    <div class="col-6" id="programsList">
                        @include('back.layouts.core.forms.select',
                            [
                                'name'          => 'program',
                                'label'         => 'Program' ,
                                'class'         => 'ajax-form-field  program-field',
                                'required'      => true,
                                'attr'          => "onchange=app.getMultipleLessons(this,'groups',$lesson->id)",
                                'data'          => $programs,
                                'placeholder'   => 'Select a Program',
                                'value'         => $program->id
                        ])
                    </div>
                </div>
<div id="groupsList">
    @include('back.lessons.create-lessons.groups-list-edit')
</div>

                </div>
            </form>
            </div>
        </div>
    </div>
</div>
</div>


<script>
    $(".select2").select2();
    app.dateTimePicker();
</script>
@endsection
