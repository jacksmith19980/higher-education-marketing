<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

    <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
        @include('back.layouts.core.forms.hidden-input', [
                    'name'          => 'schedule_id[]',
                    'value'         => '',
                    'class'         => '',
                    'required'      => '',
                    'attr'          => '',
        ])
        <div class="col-md-5">
            @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'schedule_label[]',
            'label'         => 'Label' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => '',
            'data'          => ''
            ])
        </div>

        <div class="col-md-3">
            @include('back.layouts.core.forms.time-input',
            [
            'name' => 'schedule_start_time[]',
            'label' => 'Start time' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => '',
            'data' => ''
            ])

        </div>

        <div class="col-md-3">
            @include('back.layouts.core.forms.time-input',
            [
            'name' => 'schedule_end_time[]',
            'label' => 'End time' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => '',
            'data' => ''
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
</div>