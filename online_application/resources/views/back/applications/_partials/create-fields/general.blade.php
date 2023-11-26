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

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => 'onblur=app.constructFieldName(this)',
            'value'     => optional($field)->label
        ])
    </div>


    <div class="col-md-6">

        @php
            $disabled = (isset(optional($field)->name)) ? ' disabled' : ' ';
        @endphp

        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'name',
            'label'     => 'Filed Name' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => 'onkeyup=app.validateFieldName(this) ' . $disabled ,
            'value'     => optional($field)->name
        ])
    </div>
    @if (in_array($type, ['email','hidden','list','text','textarea']))
        <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'properties[map]',
                    'label'     => 'Map to contact field' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'data'      =>  ApplicationHelpers::getContactFieldMap(trim($application->object)) ,
                    'value'     => isset(optional($field)->properties['map']) ? optional($field)->properties['map'] : null
                ])
        </div>

        <div class="col-md-6">
                @include('back.layouts.core.forms.checkbox',
                [
                    'name'          => 'properties[editable]',
                    'label'         => 'Editable Mapped Field',
                    'class'         => 'ajax-form-field' ,
                    'required'      => false,
                    'attr'          => '',
                    'helper_text'   => 'Unlock Mapped field  (Can be edited)',
                    'value'         => isset(optional($field)->properties['editable']) ? optional($field)->properties['editable'] : 0,
                    'default'       => 1
                ])
        </div>
    @endif
    <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'object',
                'label'     => 'Object' ,
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'data'      =>  ['student' => 'Student' , 'parent' => 'Parent' , 'agent' => 'Agent'] ,
                'value'     =>  optional($field)->object
            ])
    </div>


    @if (!empty($contactTypes))

        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'properties[contactType]',
                'label'     => 'Contact Type' ,
                'class'     => 'ajax-form-field' ,
                'required'  => true,
                'attr'      => '',
                'data'      =>  ApplicationHelpers::getSelectionData($contactTypes) ,
                'value'     => isset(optional($field)->properties['contactType']) ? optional($field)->properties['contactType'] : 'lead'
            ])
        </div>
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
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'section',
            'label'     => 'Section' ,
            'class'     => 'ajax-form-field section' ,
            'required'  => true,
            'attr'      => '',
            'data'      => ApplicationHelpers::getSectionsList($sections->toArray()),
            'value'     => isset(optional($field)->section->id) ? optional($field)->section->id : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[default]',
            'label'     => 'Default Value' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['default']) ? optional($field)->properties['default'] : ''
        ])
    </div>


    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[placeholder]',
            'label'     => 'Placeholder text' ,
            'class'     =>'ajax-form-field placeholder' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['placeholder']) ? optional($field)->properties['placeholder'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[helper]',
            'label'     => 'Helper Text' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['helper']) ? optional($field)->properties['helper'] : ''
        ])
    </div>


</div> <!-- row -->
