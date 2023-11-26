

@include('back.layouts.core.forms.multi-select',
    [
        'name'          => 'cohorts[]',
        'label'         => 'Cohorts' ,
        'class'         =>'select2 ajax-form-field' ,
        'required'      => false,
        'attr'          => ($groups === null) ? 'disabled' : '',
        'value'         => (isset($selectedGroups)) ? $selectedGroups : [],
        'placeholder'   => 'Select Cohorts',
        'data'          => ($groups === null) ? [] : $groups,
    ])
