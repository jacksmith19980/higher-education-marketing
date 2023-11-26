<form method="POST" action="{{ route($route, ['grade'=>$grade->id]) }}" class="validation-wizard text_input_field">
    @csrf
	
	@include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'students',
        'label'     => 'students' ,
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => json_encode($students)
    ])
	
    <div class="accordion-content accordion-active">
        <div class="row">
			<div class="col-md-6">
                @include('back.layouts.core.forms.text-input',
                [
                    'name'      => 'points',
                    'label'     => 'Points' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => ''
                ])
			</div>  
        </div>
    </div>

</form>
