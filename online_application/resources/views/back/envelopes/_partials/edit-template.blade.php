<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select', [
        'name' => 'template',
        'label' => 'Template' ,
        'class' => 'ajax-form-field' ,
        'required' => false,
        'placeholder' => __('Select Template'),
        'attr' => 'onchange=app.loadEnvelopesTemplates(this)' ,
        'data' => Sign::getTemplatesList(),
        'value' => $template['id']
        ])
    </div>
</div>

@include('back.layouts.core.forms.hidden-input',
[
'name' => 'template_name',
'label' => 'Template Name' ,
'class' => 'ajax-form-field' ,
'required' => true,
'attr' => '',
'value' => isset($template['name']) ? $template['name'] : null
])
@include('back.layouts.core.forms.hidden-input',
[
'name' => 'custom_fields',
'label' => 'Custom Fields' ,
'class' => 'ajax-form-field' ,
'required' => true,
'attr' => '',
'value' => isset($template['fields']) ? $template['fields'] : null
])
<div id="applicationFieldsWrapper">
    <div class="row">
        <div class="col-12">
            @php
                $fields = json_decode( $template['fields'] , true );
            @endphp
            @if( !$fields || !count( $fields ) )
            <div class="alert alert-warning">
                {{__('Please select the template first')}}
            </div>

            @else
            {!! $fieldsMapping !!}

            @foreach ($fields as $field)
            @include('back.envelopes._partials.fields-mapping', [
            'field_name' => $field['field'],
            'field_name_value' => $field['field'],
            'template_field_name' => $field['Esignature_field'],
            ])


            @endforeach

            @endif

        </div>
    </div>
</div>
