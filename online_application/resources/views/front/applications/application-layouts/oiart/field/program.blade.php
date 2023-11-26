@php
    $courses = [];
    $programs = [];
    $student = Auth::guard('student')->user();
    $bySlug = isset($student->params['campus']) ? true : false;
    $campuses = ApplicationHelpers::getCampusesList($bySlug);

    $programTypes = ProgramHelpers::getProgramTypes();

    $program = (isset($params['value']['programs']) && !is_null($params['value']['programs'])) ? $params['value']['programs'] : (isset($student->params['program']) ? $student->params['program'] : null) ;


    $campus = (isset($params['value']['campus']) && !is_null($params['value']['campus'])) ? $params['value']['campus'] : (isset($student->params['campus']) ? $student->params['campus'] : null) ;


    $programType = (isset($params['value']['programType']) && !is_null($params['value']['programType'])) ? $params['value']['programType'] : (isset($student->params['programType']) ? $student->params['programType'] : null) ;


@endphp
<input type="hidden" name="application" value="{{ $application->id }}">
<input type="hidden" name="submission" value="{{ $submission->id ?? null}}">
<div class="w-100"></div>

@if( !$program)

            @if(isset($field->properties['showCampus']) && count($campuses) > 1)

                <div class="col-md-6">
                    @include('back.layouts.core.forms.select', [
                        'name'        => $field->name . '[campus]',
                        'label'       => 'Campus',
                        'class'       => 'ajax-form-field' ,
                        'required'    => isset($params['properties']['validation']['required']) ? false : true,
                        'attr'        => "onchange=app.searchPrograms(this,'campus','" . route('programs.filter', ['school' => $school , 'group' => isset($field->properties['groupByType']) ? true : false]) . "') data-field=".$field->id . " data-fieldName=".$field->name,
                        'placeholder' => "Select a Campus",
                        'data'        => $campuses
                    ])
                </div>
            @else
                @php
                    $programs = ApplicationHelpers::getProgramsList();
                @endphp
            @endif

            @if(isset($field->properties['showType']) && count($programTypes) > 1)
                <div class="col-md-6">
                    @include('back.layouts.core.forms.select', [
                        'name'        => $field->name . '[programType]',
                        'label'       => 'Program Type',
                        'class'       => 'ajax-form-field' ,
                        'required'    => isset($params['properties']['validation']['required']) ? false : true,
                        'attr'        => '',
                        'attr'        => "onchange=app.searchPrograms(this,'program-type','" . route('programs.filter', ['school' => $school , 'group' => isset($field->properties['groupByType']) ? true : false]) . "') data-field=".$field->id . " data-fieldName=".$field->name,
                        'placeholder' => "Select program type",
                        'data'        => $programTypes

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
                    'required'  => isset($params['properties']['validation']['required']) ? false : true,
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

            <div class="col-md-12 custom-fields"></div>

            <div class="col-md-12">
                <div class="program-addons row form-group">
                </div>
            </div>

@else
    @include('front.applications.application-layouts.oiart.programs.programs-update')
@endif

<div class="w-100"></div>
