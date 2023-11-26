@php
    $courses = [];
    $campuses = ApplicationHelpers::getCampusesList();
@endphp
<input type="hidden" name="application" value="{{ $application->id }}">
<input type="hidden" name="submission" value="{{ $submission->id ?? null}}">
<div class="w-100"></div>

<div class="col-12 program-container repeater_box">
    <div class="row">
    @if(!isset($params['value']['courses']) ||  is_null($params['value']['courses']) )

        @if($field->properties['showCampus'] && count($campuses) > 1)

            <div class="col-md-6">
                @include('back.layouts.core.forms.select', [
                    'name'        => $field->name . '[campus]',
                    'label'       => 'Campus',
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

            $list = isset(optional($field)->properties['data']['courses']) ? optional($field)->properties['data']['courses'] : null;
            if($list){
                $campus_id = array_search($list,$campuses);
            }
            if(empty($field->data)) {
                $courses = ApplicationHelpers::getCoursesList($campus_id);
            }
            else {
                $courses = $field->data;
            }
            $courses1 = ApplicationHelpers::getCoursesList();

        @endphp
    @endif

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'      => $field->name . '[courses]',
            'label'     => 'Courses',
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => "onchange=app.searchDatesAddons(this,'".route('courses.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name." disabled",
            'value'         => '',
            'placeholder'   => "Select a Course",
            'data'          => $courses
        ])
    </div>

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
@else
    @include('front.applications.application-layouts.rounded.course.courses-update')
@endif



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
</div>
