@php
    $courses = [];
    $programs = [];
    $campuses = ApplicationHelpers::getCampusesList();
    $programTypes = ProgramHelpers::getProgramTypes();
@endphp
<input type="hidden" name="application" value="{{ $application->id }}">
<input type="hidden" name="submission" value="{{ $submission->id ?? null}}">
<div class="w-100"></div>

<div class="col-12 program-container">
    <div class="row">
        @if( !isset($params['value']['programs']) ||  is_null($params['value']['programs']) )

                    @if(isset($field->properties['showCampus']) && count($campuses) > 1)

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.select', [
                                'name'        => $field->name . '[campus]',
                                'label'       => 'Campuses',
                                'class'       => 'ajax-form-field' ,
                                'required'    => isset($params['properties']['validation']['required']) ? false : true,
                                'attr'        => '',
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
    </div>
</div>
<div class="w-100"></div>
