@if(array_key_exists('campus', $params['value']))
    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'        => $field->name . '[campus]',
            'label'       => 'Campuses',
            'class'       => 'ajax-form-field' ,
            'required'    => true,
            'attr'        => '',
            'attr'        => "onchange=app.searchPrograms(this,'" . route('programs.campus', $school) . "') data-field=".$field->id . " data-fieldName=".$field->name,
            'placeholder' => "Select a Campus",
            'value'       => $params['value']['campus'],
            'data'        => $campuses
        ])
    </div>

    @php
        $programs = ApplicationHelpers::getProgramsList($params['value']['campus']);
    @endphp

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => $field->name . '[programs]',
            'label'     => 'Programs',
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => "onchange=app.searchProgramDatesAddons(this,'" . route('programs.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name,
            'value'     => $params['value']['programs'],
            'data'      => $programs
        ])
    </div>
@php
    $locale = $settings['school']['locale'];
@endphp
    <div class="col-md-12">
        <div class="program-start-date row form-group">
            @php
                $program = \App\Tenant\Models\Program::where('slug', $params['value']['programs'])->first();

                if (!is_null($program->properties['dates_type'])) {
                    switch ($program->properties['dates_type']) {
                        case 'specific-dates':
                            $datesData = [];
                            $format_date = 'l jS \\of F Y';
                            for ($i = 0; $i < count($program->properties['start_date']); $i++) {
                                $datesData[] = [
                                    'start_date' => $program->properties['start_date'][$i],
                                    'end_date' => $program->properties['end_date'][$i],
                                    'date_price' => $program->properties['date_price'][$i],
                                    'date_schudel' => $program->properties['date_schudel'][$i]
                                ];
                            }
                            break;
                        case 'specific-intakes':
                            $datesData = $program->properties['intake_date'];
                            $format_date = 'l jS \\of F Y';
                            break;
                    }
                }
            @endphp
            @if($program->properties['dates_type'] == 'specific-dates')
                @include('front.applications.application-layouts.iframe.programs.partials.specific_dates', [
                            'datesData' => $datesData,
                            'program' => $program,
                            'field' => $field,
                            'format_date' => $format_date,
                            'selected' => $params['value']['date'],
                            'locale' => $locale
                        ])
            @elseif($program->properties['dates_type'] == 'specific-intakes')
                @include('front.applications.application-layouts.iframe.programs.partials.intake_dates', [
                    'datesData' => $datesData,
                    'program' => $program,
                    'field' => $field,
                    'format_date' => $format_date,
                    'selected' => $params['value']['date'],
                    'locale' => $locale
                ])
            @endif
        </div>
    </div>

    @php
        $addonsData = [];
        if (array_key_exists('addons', $program->properties)) {
            for ($i = 0; $i < count($program->properties['addons']['addon_options']); $i++) {
                $addonsData[] = [
                    'addon_options_category' => $program->properties['addons']['addon_options_category'][$i],
                    'addon_options' => $program->properties['addons']['addon_options'][$i],
                    'addon_options_price' => $program->properties['addons']['addon_options_price'][$i]
                ];
            }
        }
    @endphp

    <div class="col-md-12">
        <div class="program-addons row form-group">
            @if(isset($params['value']['program_addons']))
                @include('front.applications.application-layouts.iframe.programs.partials.addons', [
                    'addonsData' => $addonsData,
                    'program' => $program,
                    'field' => $field,
                    'selected' => $params['value']['program_addons'],
                ])
            @endif
        </div>
    </div>
@else

    @php
        $programs = ApplicationHelpers::getProgramsList();
    @endphp

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => $field->name . '[programs]',
            'label'     => 'Programs',
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => "onchange=app.searchProgramDatesAddons(this,'" . route('programs.startDatesAndAddons', $school) ."') data-route=".route('date.addons', $school) . " data-field=".$field->id . " data-fieldName=".$field->name,
            'value'     => $params['value']['programs'],
            'data'      => $programs
        ])
    </div>
    @php
        $locale = $settings['school']['locale'];
    @endphp
    <div class="col-md-12">
        <div class="program-start-date row form-group">
            @php
                $program = \App\Tenant\Models\Program::where('slug', $params['value']['programs'])->first();

                if (!is_null($program->properties['dates_type'])) {
                    switch ($program->properties['dates_type']) {
                        case 'specific-dates':
                            $datesData = [];
                            $format_date = 'l jS \\of F Y';
                            for ($i = 0; $i < count($program->properties['start_date']); $i++) {
                                $datesData[] = [
                                    'start_date' => $program->properties['start_date'][$i],
                                    'end_date' => $program->properties['end_date'][$i],
                                    'date_price' => $program->properties['date_price'][$i],
                                    'date_schudel' => $program->properties['date_schudel'][$i]
                                ];
                            }
                            break;
                        case 'specific-intakes':
                            $datesData = $program->properties['intake_date'];
                            $format_date = 'l jS \\of F Y';
                            break;
                    }
                }
            @endphp
            @if($program->properties['dates_type'] == 'specific-dates')
                @include('front.applications.application-layouts.iframe.programs.partials.specific_dates', [
                            'datesData' => $datesData,
                            'program' => $program,
                            'field' => $field,
                            'format_date' => $format_date,
                            'selected' => $params['value']['date'],
                            'locale' => $locale
                        ])
            @elseif($program->properties['dates_type'] == 'specific-intakes')
                @include('front.applications.application-layouts.iframe.programs.partials.intake_dates', [
                    'datesData' => $datesData,
                    'program' => $program,
                    'field' => $field,
                    'format_date' => $format_date,
                    'selected' => $params['value']['date'],
                    'locale' => $locale
                ])
            @endif
        </div>
    </div>

    @php
        $addonsData = [];
        if (array_key_exists('addons', $program->properties)) {
            for ($i = 0; $i < count($program->properties['addons']['addon_options']); $i++) {
                $addonsData[] = [
                    'addon_options_category' => $program->properties['addons']['addon_options_category'][$i],
                    'addon_options' => $program->properties['addons']['addon_options'][$i],
                    'addon_options_price' => $program->properties['addons']['addon_options_price'][$i]
                ];
            }
        }
    @endphp

    <div class="col-md-12">
        <div class="program-addons row form-group">
            @if(isset($params['value']['program_addons']))
                @include('front.applications.application-layouts.iframe.programs.partials.addons', [
                    'addonsData' => $addonsData,
                    'program' => $program,
                    'field' => $field,
                    'selected' => $params['value']['program_addons'],
                ])
            @endif
        </div>
    </div>
@endif
