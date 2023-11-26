@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Grade')}}</h4>
                        <hr>

                        <form method="POST" action="{{ route('grades.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Create Grade') }}"
                              data-add-button="{{__('Save')}}">
                            @csrf

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
                                            'value'     => ''
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
                                            'value'     => ''
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
                                            'value'     => ''
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
                                            'value'         => '',
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
                                            'value'     => '',
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
                                            'value' => false,
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
                                            'value'         => ''
                                        ])
                                    </div>
                                    <div class="col-md-6 courses" id="courses">
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
                                            'value' => false,
                                            'default' => true
                                        ])
                                    </div>
                                </div>

                            <div id="rubric_section">
                                <div id="add_create_rubric">
                                    
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