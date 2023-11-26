<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'field_type',
            'label'         => 'Type' ,
            'class'         => '',
            'required'      => true,
            'attr'          => 'onchange=app.getCustomfieldForm(this)',
            'value'         => isset($customfield->field_type) ? $customfield->field_type : '',
            'placeholder'   => 'Select Type',
            'data'          => $types
        ])
    </div>
</div>

<div id="customfield-data">
    @if (isset($customfield->field_type))
        @include('back.customFields._partials.' . $customfield->field_type)
        @if ($customfield->field_type == 'list')
            @foreach($customfield->data['labels'] as $order => $key)
                @include('back.customFields._partials.list-data', [
                    'key' => $key, 'value' => $customfield->data['labels'][$order], 'order' => $order
                ])
            @endforeach
        @endif
    @endif
</div>