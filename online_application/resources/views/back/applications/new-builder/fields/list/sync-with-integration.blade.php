@include('back.layouts.core.forms.select',[
    'name'      => 'properties[data_integration]',
    'label'     => 'Sync With' ,
    'class'     => 'ajax-form-field' ,
    'required'  => true,
    'attr'      => '',
    'data'      => $list,
    'value'     => []
])
