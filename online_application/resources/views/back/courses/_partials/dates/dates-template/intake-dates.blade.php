<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
    <div class="col-md-5 repeated-wrapper">
        @include('back.layouts.core.forms.date-input',
    [
        'name'          => 'properties[intake_date][]',
        'label'         => 'Intake date' ,
        'class'         => '' ,
        'required'      => false,
        'attr'          => '',
        'value'         => '',
        'data'          => ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.multi-select',
        [
            'name'      => 'properties[campuses][]',
            'label'     => 'Campus' ,
            'class'     =>'select2' ,
            'required'  => true,
            'attr'      => '',
            'value'     => '',
            'data'      => ProgramHelpers::campusesList()
        ])
    </div>

    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</div>