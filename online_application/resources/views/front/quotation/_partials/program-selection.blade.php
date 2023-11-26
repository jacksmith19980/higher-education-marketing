@if (isset($quotation->properties['campuses']))
    {{-- Campus Selection --}}
    @include('front.quotation._partials.campus.campus')
    
@else
    {{-- Program Selection --}}
    @include('front.quotation._partials.course.course')

@endif

<div class="courseSelection"></div>
<div class="dateSelection"></div>
<div class="miscDetails"></div>