@php
    $attr = 'onchange=app.nextStep(this) data-get=courses data-by=dates data-quotation='.$quotation->id;
    $key = rand(100,999);
@endphp

<div class="form-group">
        @include('back.layouts.core.forms.checkbox-group',
        [
            'name'          => "dates[]",
            'label'         => __("Which weeks would you like to book?") ,
            'class'         => 'select2 quotationDateSelect' ,
            'required'      => true,
            'attr'          => $attr,
            'value'         => '',
            'placeholder'   => __('select'),
            'helper'        => __('You can select multiple weeks'),
            'data'          => QuotationHelpers::getQuotationDates( $quotation->properties['courses'] , $quotation->properties['campuses']  ),
        ])
</div>