
<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
            [
            'name'          => "hubspot[custom_properties][$order][property]",
            'label'         => 'Custom Property' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => isset($property['property']) ? $property['property'] : null,
            'placeholder'   => 'Select Custom Property',
            'data'          => HubspotHelper::getCustomProperties('student')
        ])
    </div>

    <div class="col-md-5">
        @include('back.layouts.core.forms.text-input',
        [
        'name'      => "hubspot[custom_properties][$order][value]",
        'label'     => 'Value' ,
        'class'     => 'ajax-form-field' ,
        'required'  => false,
        'attr'      => '',
        'value'     => isset($property['value']) ? $property['value'] : null,
        'data'      => ''
        ])
    </div>
    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>

</div>
