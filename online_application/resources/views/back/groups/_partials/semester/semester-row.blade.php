<div style="display: flex; flex-wrap: wrap;" class="col-md-12 semester-row">
    <div style="display: flex; flex-wrap: wrap;" class="col-md-12 semester-row">

        <div class="col-md-3">
            <div class="form-group">
                @include('back.layouts.core.forms.date-input',
                [
                    'name'      => 'properties[start_date][]',
                    'label'     => 'Start at' ,
                    'class'     =>'' ,
                    'required'  => false,
                    'attr'      => '',
                    'value'     => '',
                    'data'      => ''
                ])
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                @include('back.layouts.core.forms.date-input',
            [
                'name'      => 'properties[end_date][]',
                'label'     => 'End Date' ,
                'class'     =>'' ,
                'required'  => false,
                'attr'      => '',
                'value'     => '',
                'data'      => ''
            ])
            </div>
        </div>

        <div class="col-md-5">
            @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'properties[semester_label][]',
            'label'         => 'Label' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => '',
            'data'          => ''
            ])
        </div>

        <div class="col-md-1 action_wrapper">
            <div class="form-group m-t-27">
                <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'semester-row')">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
    </div>
</div>