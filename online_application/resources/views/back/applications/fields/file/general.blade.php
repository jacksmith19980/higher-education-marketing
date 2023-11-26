<div class="row">

    @include('back.layouts.core.forms.hidden-input',
    [
    'name' => 'properties[type]',
    'label' => 'Type' ,
    'class' =>'ajax-form-field' ,
    'required' => true,
    'attr' => '',
    'value' => $type
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
    'name' => 'field_type',
    'label' => 'Type' ,
    'class' =>'ajax-form-field' ,
    'required' => true,
    'attr' => '',
    'value' => $field_type
    ])

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'title',
        'label' => 'Title' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => 'onblur=app.constructFieldName(this)',
        'value' => isset(optional($field)->label) ? optional($field)->label : ''
        ])
    </div>

    <div class="col-md-6">

        @php
        $disabled = (isset(optional($field)->name)) ? ' disabled' : ' ';
        @endphp

        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'name',
        'label' => 'Filed Name' ,
        'class' =>'ajax-form-field' ,
        'required' => true,
        'attr' => 'onkeyup=app.validateFieldName(this) ' . $disabled ,
        'value' => isset(optional($field)->name) ? optional($field)->name : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'object',
        'label' => 'Object' ,
        'class' => 'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'data' => ['student' => 'Student' , 'parent' => 'Parent' , 'agent' => 'Agent'] ,
        'value' => isset(optional($field)->object) ? optional($field)->object : ''
        ])
    </div>

    @if (!empty($contactTypes))

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'properties[contactType]',
        'label' => 'Contact Type' ,
        'class' => 'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'data' => ApplicationHelpers::getSelectionData($contactTypes) ,
        'value' => isset(optional($field)->properties['contactType']) ? optional($field)->properties['contactType'] :
        'lead'
        ])
    </div>
    @else
    @include('back.layouts.core.forms.hidden-input',
    [
    'name' => 'properties[contactType]',
    'label' => 'Type' ,
    'class' =>'ajax-form-field' ,
    'required' => true,
    'attr' => '',
    'value' => 'lead'
    ])
    @endif

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'section',
        'label' => 'Section' ,
        'class' => 'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'data' => ApplicationHelpers::getSectionsList($sections->toArray()),
        'value' => isset(optional($field)->section->id) ? optional($field)->section->id : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[placeholder]',
        'label' => 'Placeholder text' ,
        'class' =>'ajax-form-field' ,
        'required' => false,
        'attr' => '',
        'value' => isset(optional($field)->properties['placeholder']) ? optional($field)->properties['placeholder'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[helper]',
        'label' => 'Helper Text' ,
        'class' =>'ajax-form-field' ,
        'required' => false,
        'attr' => '',
        'value' => isset(optional($field)->properties['helper']) ? optional($field)->properties['helper'] : ''
        ])
    </div>
    <div class="col-md-12">
        @include('back.layouts.core.forms.multi-select',
        [
        'name' => 'properties[allowed][]',
        'label' => 'Allowed files' ,
        'class' =>'ajax-form-field select2' ,
        'required' => false,
        'attr' => '',
        'data' => [
        'jpg' => 'jpg',
        'jpeg' => 'jpeg',
        'png' => 'png',
        'doc' => 'doc',
        'pdf' => 'pdf',
        'docx' => 'docx',
        ],
        'value' => (isset(optional($field)->properties['allowed'])) ? optional($field)->properties['allowed'] : 'pdf'
        ])
    </div>
</div> <!-- row -->
