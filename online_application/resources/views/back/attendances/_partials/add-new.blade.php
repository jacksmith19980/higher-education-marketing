<form id="attendance-form" method="POST" action="{{ route('attendances.store.new') }}"
    class="validation-wizard text_input_field">
    @csrf

    <div class="row">
        <div class="col-md-6">
            @include('back.layouts.core.forms.date-input', [
                'name' => 'start_date',
                'label' => 'Start Date',
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'value' => '',
                'data' => '',
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.select', [
                'name' => 'instructor',
                'label' => 'Instructor',
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'data' => $instructors,
                'placeholder' => 'Select an Instructor',
                'value' => '',
            ])
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            @include('back.layouts.core.forms.select', [
                'name' => 'student',
                'label' => 'Student',
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'data' => $students,
                'placeholder' => 'Select a Student',
                'value' => '',
            ])
        </div>

        <div class="col-md-4">
            @include('back.layouts.core.forms.select', [
                'name' => 'course',
                'label' => 'Course',
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'data' => $courses,
                'placeholder' => 'Select a Course',
                'value' => '',
            ])
        </div>

        <div class="col-md-4">
            @include('back.layouts.core.forms.select', [
                'name' => 'status',
                'label' => 'Status',
                'class' => 'ajax-form-field',
                'required' => true,
                'attr' => '',
                'data' => [
                    'présent - classe' => 'Présent - classe',
                    'présent - en ligne' => 'Présent - en ligne',
                    'absent' => 'Absent',
                    'retard' => 'Retard',
                    'withdrawn' => 'Withdrawn',
                ],
                'placeholder' => 'Select a Status',
                'value' => '',
            ])
        </div>
    </div>

    <div class="row">

    </div>

    <div class="slots"></div>
</form>
