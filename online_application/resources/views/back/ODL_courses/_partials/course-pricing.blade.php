<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'properties[price_type]',
            'label'         => 'Course Prices' ,
            'class'         =>'' ,
            'required'      => true,
            'attr'          => 'onchange=app.loadCoursePricing(this)',
            'value'         => isset($course->properties['price_type']) ? $course->properties['price_type'] : '',
            'data'          => [
                    null          => 'Select Cours Pricing Type',
                    'flat-rate' => 'Flat Rate/Week'
                ]
            ])
    </div>
</div>
<div class="row loadCoursePricing">
    @if (isset($course->properties['price_type']))
        @include('back.courses._partials.pricing-template.' . $course->properties['price_type'])
    @endif
</div>