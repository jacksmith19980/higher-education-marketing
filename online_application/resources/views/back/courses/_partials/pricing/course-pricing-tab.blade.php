<div class="tab-pane fade show active" id="pricing" role="tabpanel" aria-labelledby="pills-pricing-tab">
    <div class="card-body">
        
        <div class="row">

            <div class="col-md-10">
                <form method="post" action="{{route('courses.properties' , $course)}}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    
                    @csrf

                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'properties[pricing][price_type]',
                    'label' => 'Course Prices' ,
                    'class' =>'' ,
                    'required' => true,
                    'attr' => 'onchange=app.loadCoursePricing(this)',
                    'value' => isset($course->properties['pricing']['price_type']) ? $course->properties['pricing']['price_type'] : '',
                    'data' => [
                    null => 'Select Cours Pricing Type',
                    'flat-rate' => 'Flat Rate/Week'
                    ]
                    ])
                    <div class="row loadCoursePricing">
                        @if (isset($course->properties['pricing']['price_type']))
                            @include('back.courses._partials.pricing.pricing-template.' . $course->properties['pricing']['price_type'])
                        @endif
                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-success float-right">Save</button>
                    </div>

                </form>
            </div>

        </div>
        
    </div>
</div>