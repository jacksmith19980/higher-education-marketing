<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Dates',
            'action'        => 'program.addSpecificIntakes',
            'container'     => '#specific_dates_wrapper',
            'count'         => isset($program->properties['intake_date']) ? count($program->properties['intake_date']) : 0
        ])
    </div>
</div>
<div class="row" id="specific_dates_wrapper">

    @if (isset($program->properties['intake_date']) && isset($program->properties['campuses']))
        @php
            if(isset($program->properties['campuses']))
            {
                $capuses = array_values($program->properties['campuses']);
            }
        @endphp

        @foreach ($program->properties['intake_date'] as $key=>$startDate )
            <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

                <div class="col-md-6">
                    @include('back.layouts.core.forms.date-input',
                [
                    'name'          => 'properties[intake_date][]',
                    'label'         => 'Intake date' ,
                    'class'         => '' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => isset($startDate) ? $startDate : '',
                    'data'          => ''
                    ])
                </div>

                <div class="col-md-5">
                    @include('back.layouts.core.forms.multi-select',
                [
                    'name'          => 'properties[campuses]['.$key.'][]',
                    'label'         => 'Campus' ,
                    'class'         => '' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => $capuses[$key],
                    'data'          => ProgramHelpers::campusesList()
                    ])
                </div>

                <div class="col-md-1 action_wrapper">
                    <div class="form-group m-t-27">
                        <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>

        @endforeach
    @endif
</div>

