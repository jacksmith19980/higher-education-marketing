@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Grade')}}</h4>
                        <hr>

                        <form method="POST" action="{{ route('grades.update', $grade->id) }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update Grade') }}"
                              data-add-button="{{__('Save')}}">
                            @csrf
                            @method('patch')

                            <h6>{{__('Information')}}</h6>
                            <section style="margin-bottom: 30px">

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'title',
                                            'label'     => 'Title' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => isset($grade) ? $grade->title : ''
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'points',
                                            'label'     => 'Points' ,
                                            'class'     =>'' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => isset($grade) ? $grade->points : ''
                                        ])
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'description',
                                            'label'     => 'Description' ,
                                            'class'     =>'' ,
                                            'required'  => false,
                                            'attr'      => '',
                                            'value'     => isset($grade) ? $grade->description : ''
                                        ])
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.date-input', [
                                            'name'          => 'due_date',
                                            'label'         => 'Due date' ,
                                            'class'         => '' ,
                                            'required'      => false,
                                            'attr'          => 'autocomplete=off',
                                            'value'         => isset($grade) ? $grade->due_date : '',
                                            'data'          => ''
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        <div onmouseenter="app.showAddSingleItem(this);" ontouchstart="app.showAddSingleItem(this);" id="{{rand()}}">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'category',
                                            'label'     => 'Category' ,
                                            'class'     =>'new-single-item' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => isset($grade) ? $grade->category_id : '',
                                            'placeholder' => 'Select a category',
                                            'data'      => $categories,
                                            'addNewRoute'   => route('educationalservicecategories.store')
                                        ])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        @include('back.layouts.core.forms.switch',
                                        [
                                            'name' => 'for_course',
                                            'label' => __("Link Courses"),
                                            'class' => 'switch ajax-form-field',
                                            'required' => true,
                                            'attr' => 'data-on-text=Yes data-off-text=No onchange=app.addProgramCoursesOnchange(this)',
                                            'helper_text' => '',
                                            'value' => isset($grade) && $grade->course_id != null ? true : false,
                                            'default' => true
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'          => 'program',
                                            'label'         => 'Program' ,
                                            'class'         => 'ajax-form-field  program-field',
                                            'required'      => true,
                                            'attr'          => '',
                                            'data'          => $programs,
                                            'placeholder'   => 'Select a Program',
                                            'value'         => isset($grade) ? $grade->program_id : ''
                                        ])
                                    </div>
                                    <div class="col-md-6 courses" id="courses">
                                        @if (isset($grade) && $grade->course_id)
                                            @include('back.layouts.core.forms.select',
                                            [
                                                'name'      => 'course',
                                                'label'     => 'Course' ,
                                                'class'     =>'select2' ,
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => $grade->course_id,
                                                'placeholder' => 'Select a course',
                                                'data'      => $courses
                                            ])
                                        @endif
                                        <!--
                                        @ include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'course',
                                            'label'     => 'Course' ,
                                            'class'     =>'select2' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => '',
                                            'placeholder' => 'Select a course',
                                            'data'      => $courses
                                        ])
                                        -->
                                    </div>
                                </div>

                            </section>

                            <h6>{{__('Create/Add Rubric')}}</h6>
                            <section>

                                <div class="row" style="justify-content: center;">
                                    <div class="form-group" style="margin-left: 10px;">
                                        @include('back.layouts.core.forms.switch',
                                        [
                                            'name' => 'rubric_enabled',
                                            'label' => __("Enable Rubric"),
                                            'class' => 'switch ajax-form-field',
                                            'required' => true,
                                            'attr' => 'data-on-text=Yes data-off-text=No onchange=switchGradeRubric()',
                                            'helper_text' => '',
                                            'value' => isset($grade) && $grade->rubric == 1 ? true : false,
                                            'default' => true
                                        ])
                                    </div>
                                </div>

                            <div id="rubric_section">
                                <div id="add_create_rubric">
                                    @if (isset($grade) && $grade->rubric == 1)
                                    @foreach ($rubrics as $rubric)
                                        <div class="row">
                                            <div class="col-md-12">
                                                @include('back.layouts.core.forms.text-input',
                                                [
                                                    'name'      => 'rubric_title',
                                                    'label'     => 'Title' ,
                                                    'class'     =>'' ,
                                                    'required'  => true,
                                                    'attr'      => '',
                                                    'value'     => $rubric->title
                                                ])
                                            </div>
                                        </div> 

                                        <div class="row">
                                            <div class="col-md-12">
                                                @include('back.layouts.core.forms.text-input',
                                                [
                                                    'name'      => 'rubric_description',
                                                    'label'     => 'Description' ,
                                                    'class'     =>'' ,
                                                    'required'  => false,
                                                    'attr'      => '',
                                                    'value'     => isset($rubric->description) ? $rubric->description : ''
                                                ])
                                            </div>
                                        </div> 
                                        
                                        <div class="row repeated_fields" id="rubric_fields" >
                                            <div class="col-md-4">
                                                <label for="level_titles">Level Title<span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-lg" required id="level_titles" name="level_titles[]"
                                                        placeholder="">
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="level_descriptions">Level Description</label>
                                                    <input type="text" class="form-control form-control-lg" id="level_descriptions" name="level_descriptions[]"
                                                        placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="level_min_points">Minimum Points<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-lg" required id="level_min_points" name="level_min_points[]"
                                                        placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-1 action_wrapper" style="margin-top: 28px;">
                                                <div class="form-group action_button">
                                                    <button class="btn waves-effect waves-light btn-outline-success float-right btn-lg ml-2 mb-3" type="button"
                                                        onclick="app.repeat_fields()"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="repeated_fields_wrapper" data-parent="rubric_fields" style="margin-bottom: 25px">
                                            @foreach ($rubric->levels as $level)
                                                <div class="row removeclass_2" id="rubric_fields">
                                                    <div class="col-md-4">
                                                        <label for="level_titles">Level Title<span class="text-danger">*</span></label>
                                                        <div class="form-group">
                                                            <input type="text" value="{{ $level->title }}" class="form-control form-control-lg" required="" id="level_titles" name="level_titles[]" placeholder="">
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="level_descriptions">Level Description</label>
                                                            <input type="text" value="{{ isset($level->description) ? $level->description : '' }}" class="form-control form-control-lg" id="level_descriptions" name="level_descriptions[]" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="level_min_points">Minimum Points<span class="text-danger">*</span></label>
                                                            <input type="text" value="{{ $level->minimum_points }}" class="form-control form-control-lg" required="" id="level_min_points" name="level_min_points[]" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 action_wrapper" style="margin-top: 28px;">
                                                        
                                                    <div class="form-group">
        
                                                    <button class="btn waves-effect waves-light btn-outline-danger float-right btn-lg ml-2 mb-3" type="button" onclick="app.removeRepeatedElement('removeclass_2');"> <i class="fa fa-minus"> </i> </button> </div> </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                    @endforeach
                                        
                                    @endif
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

@section('scripts')
<script>

function switchGradeRubric() {
    if(document.getElementById('rubric_enabled').checked) {
        console.log('rubric enables');
        document.getElementById('add_create_rubric').remove();
        document.getElementById("rubric_section").innerHTML = '<div id="add_create_rubric"> <div class="row"> <div class="col-md-12"> <div class="form-group"> <label for="rubric_title">Titre<span class="text-danger">*</span></label> <input id="rubric_title" type="text" class="form-control form-control-lg" name="rubric_title" value="" required=""> </div> </div></div> <div class="row"> <div class="col-md-12"> <div class="form-group"> <label for="rubric_description">Description</label> <input id="rubric_description" type="text" class="form-control form-control-lg" name="rubric_description" value=""> </div> </div></div> <div class="row repeated_fields" id="rubric_fields"> <div class="col-md-4"> <label for="level_titles">Level Title<span class="text-danger">*</span></label> <div class="form-group"> <input type="text" class="form-control form-control-lg" required="" id="level_titles" name="level_titles[]" placeholder=""> </div> </div> <div class="col-md-4"> <div class="form-group"> <label for="level_descriptions">Level Description</label> <input type="text" class="form-control form-control-lg" id="level_descriptions" name="level_descriptions[]" placeholder=""> </div> </div> <div class="col-md-3"> <div class="form-group"> <label for="level_min_points">Minimum Points<span class="text-danger">*</span></label> <input type="text" class="form-control form-control-lg" required="" id="level_min_points" name="level_min_points[]" placeholder=""> </div> </div> <div class="col-md-1 action_wrapper" style="margin-top: 28px;"> <div class="form-group action_button"> <button class="btn waves-effect waves-light btn-outline-success float-right btn-lg ml-2 mb-3" type="button" onclick="app.repeat_fields()"><i class="fa fa-plus"></i></button> </div> </div></div><div class="repeated_fields_wrapper" data-parent="rubric_fields" style="margin-bottom: 25px"></div> </div>'; 
    } else {
        console.log('rubric disabled');
        document.getElementById('add_create_rubric').remove();
        document.getElementById("rubric_section").innerHTML = '<div id="add_create_rubric">  </div>'; 
    }
}

</script>
@endsection