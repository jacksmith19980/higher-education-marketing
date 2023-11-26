<div class="accordion-head bg-info text-white">{{__('Contacts')}}</div>
<div class="accordion-content accordion-active">
<div class="row">
@if (in_array($type, ['email','hidden','list','text','textarea']))
        <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name'      => 'properties[map]',
                    'label'     => 'Map to contact field' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => false,
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
</div>
</div>
