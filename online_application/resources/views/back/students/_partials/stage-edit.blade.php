<form method="POST" action="{{ route($route , $student ) }}" class="validation-wizard text_input_field">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div class="accordion-head bg-info text-white">Update {{$student->name}} Stage</div>

    <div class="accordion-content accordion-active">

        <div class="row">
            <div class="col-md-12">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'stage',
                    'label'     => 'Stage' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'data'      => SubmissionHelpers::getStagesList(),
                    'value'     => (isset($student->stage)) ? $student->stage : 'student'
                ])

            </div>
        </div>
    </div>

</form>
