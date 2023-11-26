<form method="POST" action="{{ route($route , $student ) }}" class="validation-wizard text_input_field">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div class="accordion-head bg-info text-white">Update {{$student->name}} Number</div>

    <div class="accordion-content accordion-active">

        <div class="row">
            <div class="col-md-12">
                @include('back.layouts.core.forms.text-input',
                [
                    'name'      => 'uuid',
                    'label'     => 'Student Number' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'data'      => '',
                    'value'     => (isset($student->uuid)) ? $student->uuid : ''
                ])

            </div>
        </div>
    </div>

</form>
