@php
    $readOnly = !($properties['editable']) ? " readonly" : ' '
@endphp

<div class="col-md-6"  {{($properties['hidden']) ? 'hidden' : ''}}>
    @include('back.layouts.core.forms.select', [
        'name'          => $properties['name'],
        'label'         => $properties['label']['text'],
        'class'         => $properties['name'] . ' customFieldInput' ,
        'required'      => true,
        'attr'          => 'onchange=app.customFieldChanged(this)' . $readOnly . ' data-customField=' . $customFieldName,
        'value'         => $properties['value'],
        'placeholder'   => "Select " . $properties['label']['text'],
        'data'          => $properties['data']
    ])
</div>
