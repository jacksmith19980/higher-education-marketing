<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Dates1111111',
            'action'        => 'course.addSpecificIntakes',
            'container'     => '#specific_dates_wrapper'
        ])
    </div>
</div>
<div class="row" id="specific_dates_wrapper">

    @if (isset($course->properties['intake_date']) && isset($course->properties['campuses']))

        @foreach ($course->properties['intake_date'] as $key=>$startDate )
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
                    'name'          => 'properties[campuses][]',
                    'label'         => 'Campus' ,
                    'class'         => '' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => isset($course->properties['campuses'][$key]) ? $course->properties['campuses'][$key] : '',
                    'data'          => ProgramHelpers::campusesList()
                    ])
                </div>

                <div class="col-md-1 action_wrapper">
                    <div class="form-group m-t-27">
                        <button class="btn btn-danger" type="button" onclick="">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>

        @endforeach
    @endif
</div>

