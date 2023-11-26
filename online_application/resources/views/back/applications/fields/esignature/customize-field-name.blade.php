@if (isset($fieldsSelect))

	<div class="col-md-5">
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

	@if (isset($esignatureFieldsSelect) && !empty($esignatureFieldsSelect))
		<div class="col-md-5">
			@include('back.layouts.core.forms.select',
            [
                'name'      => 'mautic_field_alias',
                'label'     => 'Document Field' ,
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'data'      => $esignatureFieldsSelect,
                'value'     => ''
            ])
		</div>
	@else

		<div class="col-md-5">
			@include('back.layouts.core.forms.text-input',
            [
                'name'      => 'mautic_field_alias',
                'label'     => 'Document Field' ,
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'value'     => ''
            ])
		</div>
	@endif

	<div class="col-md-1">
		<div class="form-group">
			<label>&nbsp;</label>
			<button class="btn btn-success" type="button" onclick="app.addCustomFieldNameForEsignature(this)">
					<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
@else
	<div class="col-md-5" data-field-name="{{$field_name}}">
		@include('back.layouts.core.forms.text-input',
			[
				'name'      	=> 'custom_field_name[]',
				'label'     	=> null ,
				'placeholder'	=> 'Field',
				'class'     	=> 'ajax-form-field' ,
				'required'  	=> false,
				'attr'      	=> 'disabled data-value=',
				'value'     	=> $field_name
			])
	</div>

	<div class="col-md-5" data-field-name="{{$field_name}}">
		@include('back.layouts.core.forms.text-input',
			[
				'name'      	=> 'custom_mautic_field_alias[]',
				'label'     	=> null ,
				'placeholder'	=> 'Document Field',
				'class'     	=> 'ajax-form-field' ,
				'required'  	=> false,
				'attr'      	=> 'disabled data-value=',
				'value'     	=> $custom_field_name
			])
	</div>

	<div class="col-md-1" data-field-name="{{$field_name}}">
		<div class="form-group">
			<button class="btn btn-danger" type="button" onclick="app.removeMapEsignatureField('{{$field_name}}')">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>

@endif
