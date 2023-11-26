<form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
    @csrf
	
	@include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'attendances',
        'label'     => 'attendances' ,
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => json_encode($attendances)
    ])
	
    <div class="accordion-content accordion-active">
        <div class="row">
			<div class="col-md-6">
				@include('back.layouts.core.forms.select',
				[
					'name'          => 'status',
					'label'         => 'Status' ,
					'class'         => 'ajax-form-field',
					'required'      => true,
					'attr'          => '',
					'data'      	=> [
							'présent - classe'      => 'Présent - classe',
							'présent - en ligne'    => 'Présent - en ligne',
							'absent'                => 'Absent',
							'retard'                => 'Retard',
							'withdrawn'             => 'Withdrawn',
					],
					'placeholder'   => 'Select a Status',
					'value'         => ''
				])
			</div>  
        </div>
    </div>

</form>
