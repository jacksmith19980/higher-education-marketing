<div class="row">
    <div class="col-md-6">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'custom_field_names',
                'label'         => 'Customize field names',
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => 'onchange=app.loadApplicationFields(this) data-application-id='.$application->id,
                'helper_text'   => 'Customiz field names pushed to Mautic',
                'default'       => 1,
                'value'         => isset($integration->data['custom_field_names']) ? $integration->data['custom_field_names'] : false,
            ])
    </div>

    @if (isset($integration->data['custom_field_names']))
        <div class="col-md-6">
            <a href="javascript:void(0)" class="btn btn-success float-right spinner" onclick="app.loadMoreApplicationFields(this)" style="margin-top:16px">Add More Fields</a>
        </div>
    @endif
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.hidden-input',
    [
        'name'      => 'mautic_field_pairs',
        'label'     => 'Mautic Fields Pair' ,
        'class'     => 'ajax-form-field' ,
        'required'  => true,
        'attr'      => '',
        'value'     => isset($integration->data['mautic_field_pairs']) ? json_encode($integration->data['mautic_field_pairs']) : ''
    ])
</div>

<div id="applicationFieldsWrapper" class="row">
    @if (isset($integration->data['mautic_field_pairs']) && !empty($integration->data['mautic_field_pairs']))
            @foreach ($integration->data['mautic_field_pairs'] as $field)
                  @include('back.applications.integrations.mautic.customize-field-name', [
                        'field_name'            => $field['field'] ,
                        'custom_field_name'     => $field['mautic_field'] ,
                        'mautic_contact_type'   => $field['mautic_contact_type'],
                        'field_value'           => $field['field'],
                        'custom_field_value'    => $field['mautic_field'] ,
                        ] )
            @endforeach
    @endif
</div>
