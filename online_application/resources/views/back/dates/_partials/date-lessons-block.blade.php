<div class="row slot-row">
    <div class="col-md-3">
        @include('back.layouts.core.forms.select', [
            'name'          => "lessons[$order][instructor]",
            'label'         => 'Instructor',
            'class'         => 'ajax-form-field instructor-select' ,
            'required'      => true,
            'attr'          => '',
            'data'          => isset($instructors) ? $instructors : [],
            'placeholder'   => 'Select an instructor',
            'value'         => ''
        ])
    </div>

    <div class="col-md-3">
        @include('back.layouts.core.forms.select', [
            'name'          => "lessons[$order][classroom]",
            'label'         => 'Classroom' ,
            'class'         => 'ajax-form-field classroom-select',
            'required'      => true,
            'attr'          => '',
            'placeholder'   => 'Select a classroom',
            'value'         => '',
            'data'          => [
                1 => 'Class Room 1'
            ]
        ])
    </div>

    <div class="col-md-2">
        @include('back.layouts.core.forms.select', [
            'name'          => "lessons[$order][week]",
            'label'         => 'Week day' ,
            'class'         => 'ajax-form-field',
            'required'      => true,
            'attr'          => 'onchange=app.searchMultiSlots(this) data-order='.$order,
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

    <div class="col-md-4">

        <div class="d-flex justify-content-between">

            <div id="classRoomSlotsContainer_{{$order}}">
                @include('back.dates._partials.date-classroom-slots' , [
                    "order" => $order,
                    "slots" => false,
                ])
            </div>

            <div class="action_wrapper mt-1">
                <label>&nbsp;</label>
                <div class="form-group">
                    <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'slot-row')">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
        </div>


    </div>
    </div>
