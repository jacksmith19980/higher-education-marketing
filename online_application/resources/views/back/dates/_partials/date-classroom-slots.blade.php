@include('back.layouts.core.forms.select', [
'name'          => "lessons[$order][classroom_slot]",
'label'         => 'Classroom Slot',
'class'         => 'ajax-form-field classroom-slot-select' ,
'required'      => true,
'attr'          =>  (!$slots) ? 'disabled' : ''   ,
'data'          =>  (!$slots) ?  [] : $slots,
'placeholder'   => 'Select a Classroom Slot',
'value'         => ''
])
