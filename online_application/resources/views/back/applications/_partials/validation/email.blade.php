<div class="row validationRuleWrapper">
	<div class="col-md-5">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      	=> 'properties[validation_required]',
            'label'     	=> 'Is' ,
            'class'     	=> '' ,
            'required' 		=> false,
            'attr'      	=> 'disabled=disabled',
            'data'      	=> [],
            'value'     	=> 'Email'
        ])
    </div>

	<div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[validation_email]',
            'label'     => 'Validation Message' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'data'      => [],
            'value'     => 'Please use a valid email address'
        ])
    </div>

    <div class="col-md-1 action_wrapper">
        <div class="form-group action_button">
        	<label>Remove</label>
            <button class="btn btn-danger" type="button" onclick="app.removeValidationRule(this , '{{$type}}')"><i class="ti ti-minus"></i></button>
        </div>
    </div>
</div>
