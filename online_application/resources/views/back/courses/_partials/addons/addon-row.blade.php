<div style="display: flex; flex-wrap: wrap;" class="col-md-12 slot-row">
    <div class="col-md-3">
        @include('back.layouts.core.forms.select',
    [
        'name'      => 'properties[addons][addon_options_category][]',
        'label'     => 'Category' ,
        'class'     =>'with-currency' ,
        'required'  => true,
        'attr'      => '',
        'value'     => (isset($category))? $category : '' ,
        'data'      => QuotationHelpers::getAddonsList(),
    ])
    </div>

    <div class="col-md-5">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[addons][addon_options][]',
            'label'     => 'Title' ,
            'class'     =>'' ,
            'required'  => true,
            'attr'      => '',
            'value'     => (isset($option))? $option : '',
            'data'      => ''
        ])
    </div>

    <div class="col-md-2">
        @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'properties[addons][addon_options_price][]',
        'label'     => 'Price' ,
        'class'     => '',
        'required'  => true,
        'attr'      => '',
        'value'     => (isset($price))? $price : '' ,
        'data'      => ''
    ])

    </div>

    <div class="col-md-1 action_wrapper">
        <div class="form-group m-t-27">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'slot-row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</div>
