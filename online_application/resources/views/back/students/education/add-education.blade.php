<form  method="POST" action="{{ route('store.student.education' , ['type' => $type,'student'=>$student]) }}" class="validation-wizard text_input_field">

    <div class="row">
        @include('back.layouts.core.forms.hidden-input',
            [
            'name' => 'type',
            'label' => '' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => $type
            ])


    @php
        $campuses = isset($settins['campuses']) ? array_keys($settins['campuses']) : [];
    @endphp

    @if($type == 'program')
        <div class="col-md-12">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'education',
                'label'     => 'Program' ,
                'class'     =>'select2 ajax-form-field' ,
                'required'  => true,
                'attr'      => 'onchange=app.getEducationDetails(this) data-type=' . $type,
                'value'     => '',
                'placeholder' => 'Select Program',
                'data'      => ProgramHelpers::getProgramInArrayOnlyTitleId($campuses),
            ])
        </div>
    @endif

    @if($type == 'course')
        <div class="col-md-12">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'education',
                'label'     => 'Course' ,
                'class'     =>'select2 ajax-form-field' ,
                'required'  => true,
                'attr'      => 'onchange=app.getEducationDetails(this) data-type=' . $type,
                'value'     => '',
                'placeholder' => 'Select Course',
                'data'      => CourseHelpers::getCoursesInArrayOnlyTitleId($campuses),
            ])
        </div>
    @endif
    </div>
    <div class="row" id="educationDetails"></div>
</form>
<script type="text/javascript">
    $(".select2").select2();
</script>
