<div class="row">

        <input type="hidden" name="object" class="ajax-form-field" value="student" />

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
            'attr'      => '',
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

    <div class="col-md-12">
    	@include('back.layouts.core.forms.html',
        [
            'name'      => 'properties[content]',
            'label'     => 'Default Value' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['content']) ? optional($field)->properties['content']
 : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.hidden-input',
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
        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'properties[placeholder]',
            'label'     => 'Placeholder text' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['placeholder']) ? optional($field)->properties['placeholder'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'properties[helper]',
            'label'     => 'Helper Text' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset(optional($field)->properties['helper']) ? optional($field)->properties['helper'] :  ''
        ])
    </div>


</div> <!-- row -->
