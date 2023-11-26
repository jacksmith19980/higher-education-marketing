@if(isset($school))
<form method="POST" action="{{ route($route, ['school'=>$school,'grade'=>$grade->id, 'student'=>$student->id]) }}" class="validation-wizard text_input_field">
@else
<form method="POST" action="{{ route($route, ['grade'=>$grade->id, 'student'=>$student->id]) }}" class="validation-wizard text_input_field">
@endif
    @csrf
	
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
                    'value'     => (isset($studentWithGrade["grade"]) && $studentWithGrade["grade"]!=null) ? $studentWithGrade["grade"]->value : ''
                ])
			</div>  
        </div>
    </div>

</form>