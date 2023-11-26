<div data-date="{{$date}}" data-program="{{$program->id}}" class="d-flex justify-content-between">
    <div class="form-group">
    @include('back.layouts.core.forms.radio-group',
    [
        'name'              => "addons[$program->id][$date][]",
        'label'             => __("Would you like to book any optional Extra Choice Activities?"),
            'class'         => '' ,
            'required'      => false,
            'attr'          => 'data-quotation='.$quotation->id,
            'value'         => 1000000,
            'placeholder'   => __('Activities'),
            'small_helper'  => "For ". QuotationHelpers::formateStartEndDates($date),
            'data'          => $addon,
            'deselect'     => false,
            ])
    </div>
</div>