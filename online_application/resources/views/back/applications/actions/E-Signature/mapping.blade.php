<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'      => 'properties[documentHash]',
            'label'     => 'Document Template' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'placeholder'  => 'Don\'t add sign to the application',
            'attr'          => 'onchange=app.loadApplicationFieldsForEsignature(this) data-application-id='.$application->id,
            'data'      => Sign::getTemplatesList(),
            'value'     => isset($action->properties['documentHash'])  ? $action->properties['documentHash'] : ''
        ])
    </div>

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'properties[custom_fields]',
        'label'     => 'Custom Fields' ,
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => isset($action->properties['custom_fields']) ? $action->properties['custom_fields'] : ''
    ])

</div>

@if (isset($action->properties['custom']['fields']))
    <div class="row">
        <div class="col-md-12 load_more_button">
            <a href="javascript:void(0)" class="float-right btn btn-success spinner"
                onclick="app.loadMoreApplicationEsignatureFields(this)" style="margin:20px 0">Add More Fields</a>
        </div>
    </div>
@endif


<div id="applicationFieldsWrapper" class="row">

    @if (isset($action->properties['custom_fields']))

        @include('back.applications.fields.esignature.customize-field-name' , [
                'fieldsSelect'              => $fieldsSelectEdit,
                'esignatureFieldsSelect'    => $EsignatureFieldsSelectEdit,
            ])

        @php
            $fields = json_decode($action->properties['custom_fields'] , true);
        @endphp

        @foreach ($fields as $field)

            @include('back.applications.fields.esignature.customize-field-name' , [
                'field_name' => $field['field'],
                'custom_field_name' => $field['Esignature_field'],
            ])

        @endforeach
    @endif
</div>
