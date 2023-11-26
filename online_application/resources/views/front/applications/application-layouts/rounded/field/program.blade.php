@php
    $courses = [];
    $programs = [];
    $campuses = ApplicationHelpers::getCampusesList();
@endphp

<input type="hidden" name="application" value="{{ $application->id }}">
<input type="hidden" name="submission" value="{{ $submission->id }}">




@if($params['value'] == '')

@if($field->properties['showCampus'] && count($campuses) > 1)

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'        => $field->name . '[campus]',
            'label'       => 'Campus',
            'class'       => 'ajax-form-field' ,
            'required'    => true,
            'attr'        => '',
            'attr'        => "onchange=app.searchPrograms(this,'" . route('programs.campus', ['school' => $school]) . "')
             data-field=".$field->id . "
             data-fieldName=".$field->name ." data-group =" . isset($field->properties['groupByType']) ? true : false ,
            'placeholder' => "Select a Campus",
            'data'        => $campuses
        ])
    </div>
@else
    @php
        $programs = ApplicationHelpers::getProgramsList();
    @endphp
@endif

<div class="col-md-6">
    @include('back.layouts.core.forms.select',
    [
        'name'      => $field->name . '[programs]',
        'label'     => 'Programs',
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => "onchange=app.searchProgramDatesAddons(this,'" . route('programs.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name,
        'value'     => '',
        'placeholder' => "Select a Program",
        'data'      => $programs
    ])
</div>

<div class="col-md-12">
    <div class="program-start-date row form-group">
    </div>
</div>

<div class="col-md-12">
    <div class="program-addons row form-group">
    </div>
</div>

@else
    @include('front.applications.application-layouts.rounded.programs.programs-update')
@endif

<div class="w-100"></div>
