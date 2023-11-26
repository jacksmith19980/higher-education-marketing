@php
    $instructor = auth()->guard('instructor')->user();
@endphp
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @foreach($instructor->courses as $course)
                    @include('front.instructor._partials.components.course.course-details' , [
                        'course'        => $course,
                        'instructor'    => $instructor,
                        'showActions'   => true
                    ])
                @endforeach
            </div>
        </div>
    </div>
</div>
