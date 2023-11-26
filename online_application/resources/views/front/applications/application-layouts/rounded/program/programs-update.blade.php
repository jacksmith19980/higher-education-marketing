
{{--  Campus  --}}
@if(isset($field->properties['showCampus']) && count($campuses) > 1)

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'        => $field->name . '[campus]',
            'label'       => 'Campus',
            'class'       => 'ajax-form-field' ,
            'required'    => isset($params['properties']['validation']['required']) ? false : true,
            'attr'        => "onchange=app.searchPrograms(this,'campus','" . route('programs.filter', ['school' => $school , 'group' => isset($field->properties['groupByType']) ? true : false]) . "') data-field=".$field->id . " data-fieldName=".$field->name,
            'placeholder' => "Select a Campus",
            'data'        => $campuses,
            'value' => isset($params['value']['campus']) ? $params['value']['campus'] : null
        ])
    </div>
@else
    @php
        $programs = ApplicationHelpers::getProgramsList();
    @endphp
@endif

{{--  Prorgam Type  --}}
@if(isset($field->properties['showType']) && count($programTypes) > 1)
    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'        => $field->name . '[programType]',
            'label'       => 'Program Type',
            'class'       => 'ajax-form-field' ,
            'required'    => isset($params['properties']['validation']['required']) ? false : true,
            'attr'        => "onchange=app.searchPrograms(this,'program-type','" . route('programs.filter', ['school' => $school , 'group' => isset($field->properties['groupByType']) ? true : false]) . "') data-field=".$field->id . " data-fieldName=".$field->name,
            'placeholder' => "Select program type",
            'data'        => $programTypes,
            'value' => isset($params['value']['programType']) ? $params['value']['programType'] : null

        ])
    </div>
@endif

@php
$programs = ApplicationHelpers::getProgramsList($params['value']['campus'] , null);
$program = isset($params['value']['programs']) ? \App\Tenant\Models\Program::whereSlug($params['value']['programs'])->first() : null;
@endphp

{{--  Program  --}}
<div class="col-md-6">
    @include('back.layouts.core.forms.select',
    [
        'name'      => $field->name . '[programs]',
        'label'     => 'Programs',
        'class'     => 'ajax-form-field' ,
        'required'  => isset($params['properties']['validation']['required']) ? false : true,
        'attr'      => "onchange=app.searchProgramDatesAddons(this,'" . route('programs.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name,
        'value' => isset($params['value']['programs']) ? $params['value']['programs'] : null,
        'placeholder' => "Select a Program",
        'data'      => $programs
    ])
</div>

{{--  Start Dates  --}}
@if(!isset($field->properties['hideStartDate']) || !$field->properties['hideStartDate'])
<div class="col-md-12">
    <div class="program-start-date row form-group">
    @php
        $datesData = ProgramHelpers::getProgramDatesDetails($program);
    @endphp
    @include('front.applications.application-layouts.rounded.program.partials.specific_dates' , [
            'datesData'     => $datesData,
            'program'       => $program ,
            'field'         => $field,
            'format_date'   =>"l jS \\of F Y",
            'selected'      => isset($params['value']['date']) ? $params['value']['date'] : 0
            ]);

    </div>
</div>
@endif

{{--  Custom Fields  --}}
@if (isset($field->properties['customFields']))
    <div class="col-md-12 custom-fields">

        @php
            $slugs = array_column($field->properties['customFields'] , 'name');
            $customFields =  CustomFieldHelper::getCustomFieldsByObjectAndSlugs('programs' , $slugs )->toArray();
        @endphp
            @include('front.applications.application-layouts.rounded.program.partials.custom-fields', [
                'customFields'  => $customFields,
                'entity'        => $program,
                'field'         => $field,
                'values'        => $params['value']
            ])
    </div>
@endif
<div class="col-md-12">
    <div class="program-addons row form-group">
    </div>
</div>
