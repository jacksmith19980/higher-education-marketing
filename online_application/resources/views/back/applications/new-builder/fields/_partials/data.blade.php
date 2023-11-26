<input type="hidden" name="data" value="custom_list" class="ajax-form-field" />
@include('back.layouts.core.forms.list-repeater' , [
    'data'          =>  FieldsHelper::prepareDateforListRepeater($field->data),
    'name'          => 'custom_data',
    'class'         => 'ajax-form-field',
    'showImport'    => true
])
