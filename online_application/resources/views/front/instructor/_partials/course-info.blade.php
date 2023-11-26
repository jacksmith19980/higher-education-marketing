
@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
</div>
@endif
@php
    $instructor = auth()->guard('instructor')->user();
@endphp

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8" style="font-size: 20px; padding-top: 10px">
                        <span>{{__('Courses')}}</span>
                    </div>
                    <div class="col-4">
                        @include('back.layouts.core.forms.select', [
                            'name'          => 'courses_select',
                            'label'         => '' ,
                            'class'         => 'select1',
                            'required'      => false,
                            'attr'          => "onchange=changeCourse() id=course_changed",
                            'value'         => $course->id,
                            'placeholder'   => 'Select course...',
                            'data'          => Arr::pluck($instructor->courses->toArray(), 'title', 'id')
                        ])
                    </div>
                </div>
                @include('front.instructor._partials.components.course.course-details' , [
                    'course'        => $course,
                    'instructor'    => $instructor,
                    'showActions'   => false
                ])
            </div>
        </div>
    </div>
</div>
