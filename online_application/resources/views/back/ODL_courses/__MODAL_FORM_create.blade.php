<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Course Title' ,
            'class'     =>'' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ''
        ])
    </div>

    <div class="col-md-12">
    <div class="row repeated_fields">
    <div class="col-md-6">
        <div class="form-group">
            @include('back.layouts.core.forms.date-input',
            [
                'name'      => 'course_start_dates[]',
                'label'     => 'Start Date' ,
                'class'     =>'' ,
                'required'  => false,
                'attr'      => '',
                'value'     => '',
                'data'      => ''
            ])
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
             @include('back.layouts.core.forms.date-input',
            [
                'name'      => 'course_end_dates[]',
                'label'     => 'End Date' ,
                'class'     =>'' ,
                'required'  => false,
                'attr'      => '',
                'value'     => '',
                'data'      => ''
            ])
        </div>
    </div>
    <div class="col-md-1 action_wrapper">
        <div class="form-group action_button">
            <label for="">{{__('Add')}}</label>

            <button class="btn btn-success" type="button" onclick="app.repeat_fields();"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>

<div class="repeated_fields_wrapper"></div>

    </div>

</div> <!-- row -->