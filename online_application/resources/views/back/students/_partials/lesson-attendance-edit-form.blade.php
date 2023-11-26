<form method="POST" action="{{ route($route, ['lesson'=>$lesson, 'student'=>$student])}}" class="validation-wizard text_input_field">
    @csrf

    <div class="accordion-head bg-info text-white">Edit Attendance</div>

    <div class="accordion-content accordion-active">

        <div class="row">

            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'status',
                    'label'     => 'Status' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'data'      => [
                        'select a status'       => 'Select a status',
                        'présent - classe'      => 'Présent - classe',
                        'présent - en ligne'    => 'Présent - en ligne',
                        'absent'                => 'Absent',
                        'retard'                => 'Retard',
                        'withdrawn'             => 'Withdrawn',
                    ],
                    'value'     => (isset($student->attendance)) ? $student->attendance->status : 'select a status'
                ])

            </div>
        </div>
    </div>

</form>
