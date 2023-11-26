<div class="row slot-row">
    <div class="col-md-5">
        @include('back.layouts.core.forms.select', [
            'name'          => 'week[' . $order . ']',
            'label'         => 'Week day' ,
            'class'         => 'ajax-form-field',
            'required'      => true,
            'attr'          => 'onchange=app.searchMultiSlots(this)',
            'placeholder'   => 'Select a week day',
            'value'         => '',
            'data'          => [
                'Monday'        => 'Monday',
                'Tuesday'       => 'Tuesday',
                'Wednesday'     => 'Wednesday',
                'Thursday'      => 'Thursday',
                'Friday'        => 'Friday',
                'Saturday'      => 'Saturday',
                'Sunday'        => 'Sunday'
            ]
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'          => 'classroom_slot[' . $order . ']',
            'label'         => 'Classroom Slot',
            'class'         => 'ajax-form-field' ,
            'required'      => true,
            'attr'          => '',
            'data'          => [],
            'attr'          => '',
            'placeholder'   => 'Select a Classroom Slot',
            'value'         => ''
        ])
    </div>

    <div class="col-md-1 action_wrapper">
        <label>&nbsp;</label>
        <div class="form-group">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'slot-row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</div>