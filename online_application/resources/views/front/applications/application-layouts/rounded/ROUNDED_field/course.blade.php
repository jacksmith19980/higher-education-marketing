@php
    $courses = [];
    $campuses = ApplicationHelpers::getCampusesList();
@endphp
<div style="padding:0" class="field_wrapper {{($properties['smart'])? 'smart-field ' : ''}} col-md-{{$properties['wrapper']['columns']}} col-sm-12 col-xs-12 no-padding {{$properties['wrapper']['class']}}" {{ (!$properties['show'])? 'data-hidden=true' : ' ' }}

	@smart($properties['smart'])

		@if (!isset($properties['logic']['type']))
			@php
				$ref_field= $properties['logic']['field']
			@endphp

		@else

			@php
			$ref_field = ( in_array($properties['logic']['type'] , ['checkbox']) ) ? $properties['logic']['field']."[]" : $properties['logic']['field'];
			@endphp

		@endif
		data-field="{{$name}}"
		data-action="{{$properties['logic']['action']}}"
		data-reference="{{$ref_field}}"
		data-operator="{{$properties['logic']['operator']}}"

		@if (!is_array($properties['logic']['value']))
			data-value="{{$properties['logic']['value']}}"
		@else
			data-value="{{implode(",",$properties['logic']['value'])}}"
		@endif

	@endsmart
	>

<input type="hidden" name="application" value="{{ $application->id }}">
<input type="hidden" name="submission" value="{{ isset($submission) ? $submission->id : null }}">


@if( !isset($params['value']['courses'][0]) ||  is_null($params['value']['courses'][0]) )

    @if($field->properties['showCampus'] && count($campuses) > 1)

        <div class="col-md-6">

            @include('back.layouts.core.forms.select', [
                'name'        => $field->name . '[campus][]',
                'label'       => 'Campuses',
                'class'       => 'ajax-form-field' ,
                'required'    => true,
                'attr'        => "onchange=app.searchCourses(this,'".route('courses.campus', $school) ."') data-field=".$field->id . " data-fieldName=".$field->name . " data-cleanroute=".route('cart.deleteCourse', $school),
                'value'       => '',
                'placeholder' => "Select a Campus",
                'data'        => $campuses,
            ])
        </div>
    @else
        @php
            if(empty($field->data)) {
                $courses = ApplicationHelpers::getCoursesList();
            }
            else {
                $courses = $field->data;
            }
            $courses1 = ApplicationHelpers::getCoursesList();

        @endphp
    @endif

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'      => $field->name . '[courses][]',
            'label'     => 'Courses',
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => "onchange=app.searchDatesAddons(this,'".route('courses.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name,
            'value'     => '',
            'placeholder' => "Select a Course",
            'data'      => $courses
        ])
    </div>
@else
    @include('front.applications.application-layouts.oiart.courses.courses-update')
@endif

<div class="col-md-12">
    <div class="start-date row form-group">
    </div>
</div>

<div class="col-md-12 custom-fields"></div>

<div class="col-md-12">
    <div class="course-addons row form-group">
    </div>
</div>

<div class="col-md-12">
    <div class="date-addons row form-group">
    </div>
</div>

<div class="col-md-12">
    <div class="courses-form row">
    </div>
</div>

@if(!empty($field->properties['coursesMultiSelection']) && $field->properties['coursesMultiSelection'] == '1')
    <div class="mb-5 col-md-12">
        <button
                id="add_course"
                type="button"
                data-route={{route('course.new.form', $school)}}
                data-max={{empty($field->properties['max']) ? 1 : $field->properties['max']}}
                data-field={{$field->id}}
                onclick=app.addCourseRow(this)
                class="btn btn-primary">{{empty($field->properties['button']['text']) ? 'Add other Course' : $field->properties['button']['text']}}
        </button>
    </div>
@endif
</div>
