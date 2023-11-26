<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'properties[startView]',
        'label' => 'Start View' ,
        'class' => 'ajax-form-field' ,
        'required' => false,
        'attr' => '',
        'data' => [
                'days' => 'Days',
                'month' => 'Month',
                'months' => 'Months',
                'year' => 'Year',
                'years' => 'Years',
        ] ,
        'value' => (isset($customfield->properties['startView'])) ? $customfield->properties['startView'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'properties[format]',
        'label' => 'Date Format' ,
        'class' => 'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'data' => ApplicationHelpers::getDateFormatList() ,
        'value' => (isset($customfield->properties['format'])) ? $customfield->properties['format'] : ''
        ])
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'properties[dateType]',
        'label' => 'Date Type' ,
        'class' => 'ajax-form-field' ,
        'required' => false,
        'attr' => "onchange=app.loadContent(this,'customfield.getDateRangeSelection',[],'customDateRange') ",
        'data' => [
                'all'       => 'Any Date',
                'future'    => 'Future Dates Only',
                'todaty'    => 'Today\'s date',
                'custom'    => 'Custom Date Range'
        ] ,
        'value' => (isset($customfield->properties['dateType'])) ? $customfield->properties['dateType'] : 'all'
        ])
    </div>
    <div class="col-md-6" id="customDateRange">
    @if(isset($customfield->properties['dateType']) && $customfield->properties['dateType'] == 'custom' )
        @include('back.customFields._partials.date.date-range')
    @endif
</div>
</div>
