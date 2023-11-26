<form method="POST" action="{{ route($route) }}" class="validation-wizard text_input_field">
    @csrf

    <div class="accordion-head bg-info text-white">{{__('Add Lesson')}}</div>

    <div class="accordion-content accordion-active">

        <div class="row">
            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'program',
                    'label'         => 'Program' ,
                    'class'         => 'ajax-form-field  program-field',
                    'required'      => true,
                    'attr'          => 'onchange=app.courseModulesGroup(this)',
                    'data'          => $programs,
                    'placeholder'   => 'Select a Program',
                    'value'         => ''
                ])
            </div>
			
			<div class="col-md-6 classtoom-holder">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'classroom',
                    'label'         => 'Classroom' ,
                    'class'         => 'ajax-form-field' ,
                    'required'      => false,
                    'attr'          => '',
                    'data'          => $classrooms,
                    'attr'          => 'onchange=app.searchAvailableSlots(this)',
                    'placeholder'   => 'Select a Classroom',
                    'value'         => ''
                ])

            </div>
		</div>
			
		<div class="row">
			<div class="col 12 col-md-12 groups">
				@include('back.layouts.core.forms.multi-select',
				[
					'name'          => 'group',
					'label'         => 'Cohort',
					'class'         => 'ajax-form-field  program-field',
					'required'      => true,
					'attr'          => 'onchange=app.courseModulesGroup(this)',
					'data'          => $groups,
					'placeholder'   => 'Select a Cohort',
					'value'         => ''
				])
			</div>
		</div>
		
		    <!-- <div class="col-md-12 semester_group_radio"></div>

           <div class="col-md-6 semesters"></div> -->

	
		<div class="row">
            <div class="col-md-6 courses">
				@include('back.shared._partials.field_value', 
				[
					'data'     => $courses,
					'name'     => 'course',
					'required' => true,
					'label'    => 'Course',
					'attr'     => 'onchange=app.courseInstructors(this)',
					'value'    => ''
				])
			</div>
            <div class="col-md-6 instructors"></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @include('back.layouts.core.forms.date-input',
                [
                'name'  => 'date',
                'label' => 'Date' ,
                'class' => 'ajax-form-field' ,
                'required' => true,
                'attr' => 'onchange=app.searchAvailableSlots(this)',
                'value' => '',
                'data' => ''
                ])
            </div>
            <div class="col-md-6 slots-available"></div>
        </div>
    </div>
</form>
