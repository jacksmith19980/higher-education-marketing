<div class="row">
<div class="col-md-6">
        @include('back.layouts.core.forms.checkbox',
        [
            'name'          => 'properties[interCallingcode]',
            'label'         => 'International calling code',
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Yes, enable international calling code',
            'value'         => (isset($customfield->properties['interCallingcode'])) ? $customfield->properties['interCallingcode'] : 0 ,
            'default'       => 1
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
             'name'     => 'properties[defaultCountry]',
            'label'     => 'Default Country' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => ApplicationHelpers::getCountryCode(),
            'value'     => (isset($customfield->properties['defaultCountry'])) ? $customfield->properties['defaultCountry'] : 'CAN',
        ])
    </div>
</div>
