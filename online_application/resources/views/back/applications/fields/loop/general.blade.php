<div class="row">

        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'properties[type]',
            'label'     => 'Type' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $type
        ])

        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'field_type',
            'label'     => 'Type' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $field_type
        ])


        @php
            $disabled = (isset(optional($field)->name)) ? ' disabled' : ' ';
        @endphp

        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'name',
            'label'     => 'Filed Name' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => 'onkeyup=app.validateFieldName(this) ' . $disabled ,
            'value'     => isset(optional($field)->name) ? optional($field)->name : ''
        ])

        @include('back.layouts.core.forms.hidden-input',
            [
                'name'      => 'object',
                'label'     => 'Object' ,
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'data'      =>  ['student' => 'Student' , 'parent' => 'Parent' , 'agent' => 'Agent'] ,
                'value'     =>  'student'
        ])

        @if (!empty($contactTypes))
            @include('back.layouts.core.forms.hidden-input',
            [
                'name'      => 'properties[contactType]',
                'label'     => 'Contact Type' ,
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'data'      => $contactTypes,
                'value'     => isset(optional($field)->properties['contactType']) ? optional($field)->properties['contactType'] : 'lead'
            ])
        @else
            @include('back.layouts.core.forms.hidden-input',
            [
                'name'      => 'properties[contactType]',
                'label'     => 'Type' ,
                'class'     =>'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'value'     => 'lead'
            ])
        @endif

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => 'onblur=app.constructFieldName(this)',
            'value'     => isset(optional($field)->label) ? optional($field)->label : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'section',
            'label'     => 'Section' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => ApplicationHelpers::getSectionsList($sections->toArray()),
            'value'     => isset(optional($field)->section->id) ? optional($field)->section->id : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[button_text]',
            'label'     => 'Button Text' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['button']['text']) ? optional($field)->properties['button']['text'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[max]',
            'label'     => 'Maximum number of repeats' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'helper'    => 'Leave empty for unlimited number',
            'value'     => isset(optional($field)->properties['max']) ? $field->properties['max'] : null
        ])
    </div>

    <div class="col-md-12">
        @include('back.layouts.core.forms.multi-select',
        [
            'name'      => 'properties[fields]',
            'label'     => 'Fields to repeat' ,
            'class'     =>'select2 ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => Arr::collapse(FieldsHelper::getApplicationFields($application->id , false)),
            'value'     => isset(optional($field)->properties['fields']) ? optional($field)->properties['fields'] : ''
        ])
    </div>

</div> <!-- row -->
