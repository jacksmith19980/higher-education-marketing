<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#FieldDetailsGeneral" aria-expanded="true" aria-controls="app_pInfo">
        <h5>{{__('Field Details')}}</h5>
            <i class="mdi mdi-plus text-primary"></i>
        </div>
    </div>

    <div id="FieldDetailsGeneral" class="collapsed show" aria-labelledby="apph_pInfo" data-parent="#FieldDetailsGeneral">
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
                        'name'      => 'field_type',
                        'label'     => 'Type' ,
                        'class'     =>'ajax-form-field' ,
                        'required'  => true,
                        'attr'      => '',
                        'value'     => $field_type
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


                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }} no-padding">

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

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                        @include('back.layouts.core.forms.select',
                        [
                            'name'      => 'object',
                            'label'     => 'Object' ,
                            'class'     => 'ajax-form-field' ,
                            'required'  => true,
                            'attr'      => '',
                            'data'      =>  [
                                'students'   => 'Student',
                                'agencies'   => 'Agency',
                                'programs'   => 'Program',
                                'courses'    => 'Course',
                            ] ,
                            'value'     =>  optional($field)->object
                        ])
                </div>

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                        @include('back.layouts.core.forms.select',
                        [
                            'name'      => 'properties[contactType]',
                            'label'     => 'Contact Type' ,
                            'class'     => 'ajax-form-field' ,
                            'required'  => true,
                            'attr'      => '',
                            'data'      =>  [
                                'student'   => 'Student',
                                'agent'     => 'Agent',
                                'parent'    => 'Parent'
                            ] ,
                            'value'     =>  isset(optional($field)->properties['contactType'])  ? optional($field)->properties['contactType'] : 'student',
                        ])
                </div>

                @include('back.layouts.core.forms.hidden-input',
                    [
                    'name'      => 'properties[contactType]',
                    'label'     => 'Type' ,
                    'class'     =>'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => 'lead'
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
                        'name'      => 'properties[default]',
                        'label'     => 'Default Value' ,
                        'class'     =>'ajax-form-field' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset(optional($field)->properties['default']) ? optional($field)->properties['default'] : ''
                    ])
                </div>


                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                    @include('back.layouts.core.forms.translateable-text',
                    [
                        'name'      => 'properties[placeholder]',
                        'label'     => 'Placeholder text' ,
                        'class'     =>'ajax-form-field placeholder' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset(optional($field)->placeholder) ? optional($field)->placeholder : '',
                        'updateURL' => null
                    ])
                </div>

                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                    @include('back.layouts.core.forms.translateable-text',
                    [
                        'name'      => 'properties[helper]',
                        'label'     => 'Helper Text' ,
                        'class'     =>'ajax-form-field' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset(optional($field)->helper_text) ? optional($field)->helper_text : '',
                        'updateURL' => null
                    ])
                </div>
                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                        @include('back.layouts.core.forms.checkbox',
                        [
                            'name'          => 'properties[readonly]',
                            'label'         => false,
                            'class'         => 'ajax-form-field' ,
                            'required'      => false,
                            'attr'          => '',
                            'helper_text'   => 'Read Only',
                            'value'         => isset(optional($field)->properties['readonly']) ? (bool) optional($field)->properties['readonly'] : 0,
                            'default'       => 1
                        ])
                </div>
                <div class="{{ $newBuilder ? "col-12 new-field" : "col-md-6" }}">
                        @include('back.layouts.core.forms.checkbox',
                        [
                            'name'          => 'properties[hidden]',
                            'label'         => false,
                            'class'         => 'ajax-form-field' ,
                            'required'      => false,
                            'attr'          => '',
                            'helper_text'   => 'Hidden Field',
                            'value'         => isset(optional($field)->properties['hidden']) ? (bool) optional($field)->properties['hidden'] : 0,
                            'default'       => 1
                        ])
                </div>

            </div> <!-- row -->
        </div>
    </div>
</div>
<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapsed"
        data-target="#IntegrationsGeneral" aria-expanded="false" aria-controls="app_pInfo">
        <h5>{{__('Integrations')}}</h5>
            <i class="mdi mdi-plus text-primary"></i>
        </div>
    </div>
    <div id="IntegrationsGeneral" class="collapsed show" aria-labelledby="apph_pInfo" data-parent="#IntegrationsGeneral">
        <div class="p-0 card-body">
            <div class="row no-padding">

                    @if(count($integrationsList))
                        @foreach ($integrationsList as $integrationName => $fields)
                            <div class="col-md-12 mt-2 new-field">
                                @include('back.layouts.core.forms.select',
                                [
                                    'name'          => "properties[integrations][$integrationName]",
                                    'label'         => ucwords($integrationName) ,
                                    'class'         => 'ajax-form-field',
                                    'required'      => false,
                                    'attr'          => '',
                                    'value'         => (isset($field->properties['integrations'][$integrationName]) && $field->properties['integrations'][$integrationName]) ? $field->properties['integrations'][$integrationName] : '',
                                    'placeholder'   => 'Select Field',
                                    'data'          => $fields
                                ])
                            </div>
                        @endforeach
                    @else

                        <div class="col-12">
                            <div class="alert alert-warning">
                                {{__('You have no active 3rd Party Integrations')}}
                                <a href="{{route('plugins.index')}}">{{__('Click Here')}}</a> {{__("to activate your integarions")}}
                            </div>
                        </div>

                    @endif

            </div>
        </div>
    </div>
</div>

{{--  Add Customizations for other field types  --}}
