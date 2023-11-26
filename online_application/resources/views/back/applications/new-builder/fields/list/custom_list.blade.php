@if(!is_null($items))
    @include('back.layouts.core.forms.list-repeater' , [
        'label'         => ucwords($object) .' List',
        'data'          => FieldsHelper::prepareDateforListRepeater($items),
        'name'          => 'data',
        'class'         => 'ajax-form-field',
        'showImport'    => false,
        'placeholders'  => [ "Title" , "Code"],
    ])
@endif
