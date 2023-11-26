@php
    $list = isset(optional($field)->properties['data']['courses']) ? optional($field)->properties['data']['courses'] : '';
@endphp

<div class="row">
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'properties[data_courses]',
            'label'         => 'Courses List' ,
            'class'         => 'ajax-form-field' ,
            'placeholder'   => 'Select courses list',
            'required'      => true,
            'attr'          => 'onchange=app.dataListChange(this) data-object=courses',
            'data'          => FieldsHelper::getCoursesDataList(),
            'value'         => $list
        ])
    </div>
</div>

<div class="custom_list_wrapper">
    @if(count($field->data))
        @include('back.layouts.core.forms.list-repeater' , [
            'label'         => 'Courses List',
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
