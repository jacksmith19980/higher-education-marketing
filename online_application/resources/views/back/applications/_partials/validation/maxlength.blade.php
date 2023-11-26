<div class="row validationRuleWrapper">
    <div class="col-md-3">
        @include('back.layouts.core.forms.text-input',
        [
            'name'          => 'properties[validation][maxlength][rule]',
            'label'         => 'Is' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => 'disabled=disabled',
            'data'          => [],
            'value'         => 'Maximum'
        ])
    </div>

    <div class="col-md-2">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[validation][maxlength][number]',
            'label'     => 'Letters' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'data'      => [],
            'value'     => isset($message['number']) ? $message['number'] : "10"
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[validation][maxlength][message]',
            'label'     => 'Validation Message' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'data'      => [],
            'value'     => isset($message['message']) ? $message['message'] : 'Maximum NN Letters'
        ])
    </div>

    <div class="col-md-1 action_wrapper">
        <div class="form-group action_button">
            <label>Remove</label>
            <button class="btn btn-danger" type="button" onclick="app.removeValidationRule(this , '{{$type}}')"><i class="ti ti-minus"></i></button>
        </div>
    </div>
</div>
