<div class="row">
    <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
        <div class="col-md-5 repeated-wrapper">
            @include('back.layouts.core.forms.text-input', [
                'name'          => 'keys['. $order .']',
                'label'         => 'Label' ,
                'class'         => '',
                'required'      => true,
                'attr'          => '',
                'value'         => isset($key) ? $key : '',
                'data'          => ''
            ])
        </div>

        <div class="col-md-5">
            @include('back.layouts.core.forms.text-input', [
                'name'      => 'values['. $order .']',
                'label'     => 'Value',
                'class'     => '',
                'required'  => true,
                'attr'      => '',
                'value'     => isset($value) ? $value : '',
                'data'      => ''
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
