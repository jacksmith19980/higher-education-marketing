<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'properties[price_type]',
            'label'         => 'Program Prices' ,
            'class'         =>'' ,
            'required'      => true,
            'attr'          => 'onchange=app.loadProgramPricing(this)',
            'value'         => isset($program->properties['price_type']) ? $program->properties['price_type'] : '',
            'data'          => [
                    null        => 'Select Program Pricing Type',
                    'flat-rate-week' => 'Flat Rate/Week',
                    'flat-rate' => 'Flat Rate',
                    'n-a'       => 'Not applicable'
                ]
            ])
    </div>
</div>
<div class="row loadCoursePricing">
    @if (isset($program->properties['price_type']))
        @include('back.programs._partials.pricing-template.' . $program->properties['price_type'])
    @endif
</div>