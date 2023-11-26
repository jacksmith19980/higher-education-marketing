@include('back.shared._partials.field_value',
                [
                    'data'     => $items,
                    'name'     => 'properties[mautic_custom_field]',
                    'required' => true,
                    'attr'     => 'onchange=app.getMauticCustomFieldData(this)',
                    'value'    => isset($selected) ? $selected : '',
                    'label'    => 'Mautic Custom Field'
                ])

<div id="mautic_fields"></div>
