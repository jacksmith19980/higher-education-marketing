@php
$data = array_combine($customfield->data['values'], $customfield->data['labels'])
@endphp
<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
    <div>
    @include('back.layouts.core.forms.hidden-input', [
            'name'          => 'name',
            'label'         => 'Name',
            'class'         => '',
            'required'      => false,
            'attr'          => 'disabled',
            'value'         => $customfield->name,
            'data'          => ''
        ])
    </div>
    @php
    $val = [];
    if (isset($obj->properties['customfields'][$customfield->slug])) {
        foreach ($obj->properties['customfields'][$customfield->slug] as $value) {
            $val = array_merge($val, $value);
        }
    }
    @endphp

    <div class="col-md-12">
        @include('back.layouts.core.forms.multi-select', [
            'name'          => 'customfields['. $customfield->slug .'][]',
            'label'         => $customfield->name,
            'class'         => '',
            'required'      => isset($customfield->data['mandatory']) && $customfield->data['mandatory'] == 1 ? true : false,
            'attr'          => '',
            'value'         => $val,
            'data'          => $data
        ])
    </div>
</div>
