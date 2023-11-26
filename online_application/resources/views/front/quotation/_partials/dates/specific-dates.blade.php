@php

    $attr = 'onchange=app.dateSelected(this) data-quotation='.$quotation->id;

    $key = rand(100,999);

@endphp



@if(isset($quotation->properties['enable_misc']))

    @php

        $attr .= ' data-enable-misc=true data-course='.$course->slug ;

    @endphp

@endif



@if (isset($selectedCourse))

    <input type="hidden" value="{{$selectedCourse->slug}}" name="courses[]" />

@endif



<div class="form-group">

        @include('back.layouts.core.forms.checkbox-group',

        [

            'name'          => "dates[$course->slug][$key][]",

            'label'         => __("Which weeks would you like to book in ".$campuse->title ."?") ,

            'class'         => 'select2 quotationDateSelect' ,

            'required'      => true,

            'attr'          => $attr,

            'value'         => '',

            'placeholder'   => __('select'),

            'helper'        => __('You can select multiple weeks'),

            'data'          => QuotationHelpers::getSpecificDatesSelect($course->properties['start_date'] , $course->properties['end_date']),

        ])

</div>