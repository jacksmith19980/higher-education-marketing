<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Semester',
            'action'        => 'semester.addSemester',
            'container'     => '#semester_wrapper'
        ])
    </div>
</div>

<div class="row" id="semester_wrapper">
    @if (isset($group->properties['start_date']) && isset($group->properties['end_date']) && !empty($group->properties['start_date']))
        @foreach ($group->properties['start_date'] as $key => $startDate )
            <div style="display: flex; flex-wrap: wrap;" class="col-md-12 semester-row">

                <div class="col-md-3">
                    <div class="form-group">
                        @include('back.layouts.core.forms.date-input',
                        [
                            'name'      => 'properties[start_date][]',
                            'label'     => 'Start at' ,
                            'class'     =>'' ,
                            'required'  => false,
                            'attr'      => '',
                            'value'     => isset($startDate) ? $startDate : '',
                            'data'      => ''
                        ])
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        @include('back.layouts.core.forms.date-input',
                    [
                        'name'      => 'properties[end_date][]',
                        'label'     => 'End Date' ,
                        'class'     =>'' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset($group->properties['end_date'][$key]) ? $group->properties['end_date'][$key] : '',
                        'data'      => ''
                    ])
                    </div>
                </div>

                <div class="col-md-5">
                    @include('back.layouts.core.forms.text-input',
                [
                    'name'          => 'properties[semester_label][]',
                    'label'         => 'Label' ,
                    'class'         => '' ,
                    'required'      => false,
                    'attr'          => '',
                    'value'         => isset($group->properties['semester_label'][$key]) ? $group->properties['semester_label'][$key] : '',
                    'data'          => ''
                    ])
                </div>

                <div class="col-md-1 action_wrapper">
                    <div class="form-group m-t-27">
                        <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'semester-row')">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>