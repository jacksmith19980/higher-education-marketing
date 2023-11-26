@if ( in_array($field_type , [ 'field' , 'file' , 'html' , 'helper' , 'file_list' ]) )
    @include('back.applications.fields.'.$type.'.form')
@else
    @include('back.applications.payments.'.$type.'.form' , [ 'gateway' => $field->properties['type'] ])
@endif
