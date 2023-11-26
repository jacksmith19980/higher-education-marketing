@php
    $list = isset(optional($field)->properties['data']['programs']) ? optional($field)->properties['data']['programs'] : '';
@endphp

<div class="row">
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'properties[data_programs]',
            'label'         => 'Program List' ,
            'class'         => 'ajax-form-field' ,
            'required'      => true,
            'placeholder'   => 'Select programs list',
            'attr'          => 'onchange=app.dataListChange(this) data-object=programs',
            'data'          => FieldsHelper::getProgramsDataList(),
            'value'         => $list
        ])
    </div>
</div>
<div class="custom_list_wrapper">
    @if(isset($field->data) && count($field->data))
        @include('back.layouts.core.forms.list-repeater' , [
            'label'         => 'Programs List',
            'data'          => $field->data,
            'name'          => 'data',
            'class'         => 'ajax-form-field',
            'showImport'    => false,
            'placeholders'  => [ "Title" , "Code"],
        ])
    @endif

    @if($list == 'sync_integration')


        @php
            $customFields = IntegrationHelpers::getIntegration()->getCustomFields();
        @endphp

        @include('back.layouts.core.forms.select',[
            'name'      => 'properties[data_integration]',
            'label'     => 'Sync With' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => (isset($customFields)) ? $customFields : [],
            'value'     => isset($field->properties['data']['integration']) ? $field->properties['data']['integration'] : ''
        ])


    @endif
</div>
