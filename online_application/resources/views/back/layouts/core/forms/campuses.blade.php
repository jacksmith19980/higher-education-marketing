
@if (count($data) > 1)

<div class="col-md-6">
    @include('back.layouts.core.forms.multi-select', [
        'name'      => 'campuses[]',
        'label'     => 'Campus' ,
        'class'     =>'select2 campus' ,
        'required'  => $required,
        'attr'      => $attr,
        'value'     => $value,
        'data'      => $data
    ])
</div>
@elseif(count($campuses) == 1)

    @include('back.layouts.core.forms.hidden-input',
    [
    'name'      => 'campuses[]',
    'label'     => 'Campus' ,
    'class'     => $class ,
    'required'  => false,
    'attr'      => $attr,
    'value'     => array_keys($data)[0],
    ])

@else

    @include('back.layouts.core.forms.hidden-input',
    [
    'name'      => 'campuses[]',
    'label'     => 'Campus' ,
    'class'     => $class ,
    'required'  => false,
    'attr'      => $attr,
    'value'     => '',
    ])

@endif
