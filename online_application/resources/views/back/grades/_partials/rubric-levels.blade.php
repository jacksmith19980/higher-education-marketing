<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'rubric_title',
            'label'     => 'Title' ,
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
            'name'      => 'rubric_description',
            'label'     => 'Description' ,
            'class'     =>'' ,
            'required'  => false,
            'attr'      => '',
            'value'     => ''
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

<div class="repeated_fields_wrapper" data-parent="rubric_fields" style="margin-bottom: 25px"></div>
