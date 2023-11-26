@php
    $disabled = (isset(optional($field)->name)) ? ' disabled' : ' ';
@endphp
<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center collapsed" data-toggle="collapse"
        data-target="#FieldDetailsGeneral" aria-expanded="false" aria-controls="app_pInfo">
        <h5>{{__('Field Details')}}</h5>
            <i class="mdi mdi-plus text-primary"></i>
        </div>
    </div>

    <div id="FieldDetailsGeneral" class="collapse show" aria-labelledby="apph_pInfo" data-parent="#FieldDetailsGeneral">
        <div class="p-0 card-body">
            <div class="row no-padding">
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
                            'name'      => 'object',
                            'label'     => 'Object' ,
                            'class'     => 'ajax-form-field' ,
                            'required'  => true,
                            'attr'      => '',
                            'data'      =>  ['student' => 'Student' , 'parent' => 'Parent' , 'agent' => 'Agent'] ,
                            'value'     =>  'student'
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

                    @include('back.layouts.core.forms.hidden-input',
                    [
                        'name'      => 'properties[wrapper_columns]',
                        'label'     => 'Columns' ,
                        'class'     =>'ajax-form-field' ,
                        'required'  => true,
                        'attr'      => '',
                        'value'     => 12
                    ])

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
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
                @include('back.layouts.core.forms.hidden-input',
                    [
                        'name'      => 'name',
                        'label'     => 'Filed Name' ,
                        'class'     =>'ajax-form-field' ,
                        'required'  => true,
                        'attr'      => 'onkeyup=app.validateFieldName(this) ' . $disabled ,
                        'value'     => optional($field)->name
                    ])

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
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


                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
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

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-12" }}">
                    @include('back.layouts.core.forms.html',
                        [
                            'name' => 'properties[details]',
                            'label' => 'Helping Details' ,
                            'class' =>'ajax-form-field' ,
                            'required' => false,
                            'attr' => '',
                            'value' => isset(optional($field)->properties['details']) ? optional($field)->properties['details']
                            : ''
                        ])
                </div>
                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-12" }}">
                    @include('back.layouts.core.forms.multi-select',
                    [
                    'name' => 'properties[allowed][]',
                    'label' => 'Allowed files' ,
                    'class' =>'ajax-form-field select2' ,
                    'required' => false,
                    'attr' => '',
                    'data' => [
                    'jpg' => 'jpg',
                    'png' => 'png',
                    'doc' => 'doc',
                    'pdf' => 'pdf',
                    'docx' => 'docx',
                    ],
                    'value' => (isset(optional($field)->properties['allowed'])) ? optional($field)->properties['allowed'] : 'pdf'
                    ])
                </div>

                @if (isset(optional($field)->data))
                    <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-12" }}">
                        @include('back.layouts.core.forms.multi-select',
                        [
                            'name' => 'properties[required][]',
                            'label' => 'Required files' ,
                            'class' =>'ajax-form-field select2' ,
                            'required' => false,
                            'attr' => '',
                            'data' =>  isset(optional($field)->data) ? optional($field)->data : [],
                            'value' => (isset(optional($field)->properties['required'])) ? optional($field)->properties['required'] : []
                        ])
                    </div>
                @endif
            </div> <!-- row -->
        </div>
    </div>
</div>
