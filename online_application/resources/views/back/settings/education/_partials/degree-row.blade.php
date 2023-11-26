<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

    <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

        <div class="col-md-5">
            @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'label[]',
            'label'         => 'Label' ,
            'class'         => '' ,
            'required'      => true,
            'attr'          => $disabled,
            'value'         => isset($label) ? $label : ''
            ])
        </div>
        <div class="col-md-5">
            @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'value[]',
            'label'         => 'Value' ,
            'class'         => '' ,
            'required'      => true,
            'attr'          => $disabled,
            'value'         => isset($value) ? $value : ''
            ])
        </div>


        <div class="col-md-1 action_wrapper">
            <div class="form-group m-t-27">
                <button class="btn btn-danger" type="button" {{$disabled}} onclick="app.deleteElementsRow(this, 'date-row')">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>

    </div>
</div>
