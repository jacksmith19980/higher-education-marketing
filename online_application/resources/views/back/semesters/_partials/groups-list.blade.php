@include('back.layouts.core.forms.multi-select',
[
    'name'      => 'groups[]',
    'label'     => 'Cohorts' ,
    'class'     => 'select2 groups',
    'required'  => true,
    'attr'      => '',
    'value'     => '',
    'data'      => $groups
])
