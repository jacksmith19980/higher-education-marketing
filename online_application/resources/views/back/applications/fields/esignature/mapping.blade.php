<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name'      => 'properties[documentHash]',
            'label'     => 'Document Template' ,
            'class'     => 'ajax-form-field' ,
            'required'  => false,
            'placeholder'  => 'Don\'t add sign to the application',
            'attr'          => 'onchange=app.loadApplicationFieldsForEversign(this) data-application-id='.$application->id,
            'data'      => Sign::getTemplatesList(),
            'value'     => ( isset( $field->properties['documentHash'] ) ) ? $field->properties['documentHash'] : ''
        ])
    </div>

    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'properties[custom_fields]',
        'label'     => 'Custom Fields' ,
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => isset($field->properties['custom']['fields']) ? json_encode($field->properties['custom']['fields']) : ''
    ])

</div>

@if (isset($field->properties['custom']['fields']))
    <div class="row">
        <div class="col-md-12 load_more_button">
            <a href="javascript:void(0)" class="float-right btn btn-success spinner"
                onclick="app.loadMoreApplicationEversignFields(this)" style="margin:20px 0">Add More Fields</a>
        </div>
    </div>
@endif


<div id="applicationFieldsWrapper" class="row">

    @if (isset($field->properties['custom']['fields']))

        @php
            $fields = json_decode($field->properties['custom']['fields'] , true);
        @endphp

        @foreach ($fields as $field)

            @include('back.applications.fields.signatureEversign.customize-field-name' , [
                'field_name'        => $field['field'],
                'custom_field_name' => $field['eversign_field'],
            ])

        @endforeach
    @endif
</div>
