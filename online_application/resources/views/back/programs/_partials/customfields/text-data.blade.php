
<div class="col-md-6">
    <div>
    @include('back.layouts.core.forms.text-input', [
            'name'          => 'customfields['. $customfield->slug .']',
            'label'         => $customfield->name,
            'class'         => '',
            'required'      => isset($customfield->data['mandatory']) ? true : false,
            'attr'          => '',
            'value'         => isset($obj->properties['customfields'][$customfield->slug]) ? $obj->properties['customfields'][$customfield->slug] : '',
            'data'          => ''
        ])
    </div>
</div>
