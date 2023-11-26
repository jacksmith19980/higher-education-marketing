<div class="form-group">
    @include('back.layouts.core.forms.checkbox-group',
    [
        'name'          => "campus[]",
        'label'         => __('Venue') ,
        'class'         => '' ,
        'required'      => true,
        'attr'          => 'onchange=app.campusSelected(this) data-quotation='.$quotation->slug,
        'value'         => '',
        'placeholder'   => __('Select venue'),
        'data'          =>QuotationHelpers::getCampusesSelection($quotation->properties['campuses']),
    ])
</div>

