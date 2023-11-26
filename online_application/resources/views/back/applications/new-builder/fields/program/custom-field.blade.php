<div class="row border radius-10 mt-3 p-2 new-field">
    <div class="col-md-12 pt-2 ">
                    @include('back.layouts.core.forms.select',
                    [
                        'name'      => 'properties[customFields]['.$order.'][name]',
                        'label'     => 'Custom field' ,
                        'class'     => 'ajax-form-field' ,
                        'required'  => false,
                        'attr'      => '',
                        'data'      =>  ApplicationHelpers::getCustomFieldMap('programs') ,
                        'value'     => isset($customField['name']) ? $customField['name'] : null
                    ])
    </div>
    <div class="col-md-6 new-field">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[customFields]['.$order.'][editable]',
                'label'         => 'Editable Field',
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Unlock field',
                //'helper'        => 'Users can edit the value',
                'value'         => isset($customField['editable']) ? 1 : 0,
                'default'       => 1
            ])
    </div>
    <div class="col-md-6 new-field">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[customFields]['.$order.'][hidden]',
                'label'         => 'Hide Field',
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Hide field',
                'value'         => isset($customField['hidden']) ? 1 : 0,
                'default'       => 1
            ])
    </div>
</div>
