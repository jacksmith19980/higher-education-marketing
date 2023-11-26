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
        @include('back.layouts.core.forms.checkbox',
        [
            'name'          => 'properties[price]',
            'label'         => 'Show Price' ,
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Show price on cart review step',
            'value'         =>  isset(optional($field)->properties['price']) ? optional($field)->properties['price'] : 0,
            'default'       => true,
        ])
    </div>

</div> <!-- row -->
