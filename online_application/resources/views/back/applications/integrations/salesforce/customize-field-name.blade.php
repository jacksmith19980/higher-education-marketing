@if (isset($fieldsSelect))
	<div class="col-md-4">
		@include('back.layouts.core.forms.select',
	    [
	        'name'      => 'field_name',
	        'label'     => 'Field' ,
	        'class'     => 'ajax-form-field' ,
	        'required'  => true,
	        'attr'      => '',
	        'data'      => $fieldsSelect,
	        'value'     => ''
	    ])
	</div>
	@if (isset($mauticFieldsSelect) && !empty($mauticFieldsSelect))
		<div class="col-md-4">
			@include('back.layouts.core.forms.select',
			[
				'name'      => 'mautic_field_alias',
				'label'     => 'Mautic Field' ,
				'class'     => 'ajax-form-field' ,
				'required'  => true,
				'attr'      => '',
				'data'      => $mauticFieldsSelect,
				'value'     => ''
			])
		</div>
	@else

	<div class="col-md-4">
		@include('back.layouts.core.forms.text-input',
		[
			'name'      => 'mautic_field_alias',
			'label'     => 'Mautic Field' ,
			'class'     => 'ajax-form-field' ,
			'required'  => true,
			'attr'      => '',
			'value'     => ''
		])
	</div>
			@endif
	<div class="col-md-3">
		@include('back.layouts.core.forms.select',
		[
			'name'      => 'mautic_contact_type',
			'label'     => 'Contact Type' ,
			'class'     => 'ajax-form-field' ,
			'required'  => true,
			'attr'      => '',
			'data'      => $settings['applications']['contact_type'],
			'value'     => ''
		])
	</div>
	<div class="col-md-1">
		 <div class="form-group">
			<label>&nbsp;</label>
			<button class="btn btn-success" type="button" onclick="app.addCustomFieldName(this)"> 
					<i class="fa fa-plus"></i> 
			</button> 
	    </div>
	</div>
@else
	<div class="col-md-4">
		@include('back.layouts.core.forms.text-input',
	    [
	        'name'      	=> 'cutom_field_name[]',
	        'label'     	=> null ,
	        'placeholder'	=> 'Field',
	        'class'     	=> 'ajax-form-field' ,
	        'required'  	=> false,
	        'attr'      	=> 'disabled data-value=',
	        'value'     	=> $field_name
	    ])
	</div>

	<div class="col-md-4">
		@include('back.layouts.core.forms.text-input',
	    [
	        'name'      	=> 'custom_mautic_field_alias[]',
	        'label'     	=> null ,
	        'placeholder'	=> 'Mautic Field',
	        'class'     	=> 'ajax-form-field' ,
	        'required'  	=> false,
	        'attr'      	=> 'disabled data-value=',
	        'value'     	=> $custom_field_name
	    ])
	</div>

	<div class="col-md-3">
		@include('back.layouts.core.forms.text-input',
	    [
	        'name'      	=> 'mautic_contact_type[]',
	        'label'     	=> null ,
	        'placeholder'	=> 'Mautic Field',
	        'class'     	=> 'ajax-form-field' ,
	        'required'  	=> false,
	        'attr'      	=> 'disabled data-value=',
	        'value'     	=> $mautic_contact_type
	    ])
	</div>
	<div class="col-md-1">
		 <div class="form-group">
			<button class="btn btn-danger" type="button" onclick="app.removeCustomField(this)"> 
				<i class="fa fa-minus"></i> 
			</button>
	    </div>
	</div>
@endif









