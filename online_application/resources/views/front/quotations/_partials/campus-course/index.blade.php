<main class="main step-1">
    <section class="section-main pb-4 pb-lg-5">
        <div class="container">
            
            @include('front.quotations._partials.general.step-header' , ['steps' => $steps])


            <div class="alert alert-danger {{$step}}" style="display: none">
                {{__($steps['current']['error_message'])}}
            </div>
            
            <div class="loop-item-container">
                <ul class="debug"></ul>

                <div class="row justify-content-md-center">
                    
                    @if (!isset($quotation['properties']['hide_campus_select']))
                        
                        @foreach ($campuses as $campus)
                            @include('front.quotations._partials.campus-course.campus' , [
                            "camnpus"  => $campus
                            ])
                        @endforeach

                    @else

                        @foreach ($courses as $course)
                            @include('front.quotations._partials.campus-course.course' , [
                                "course" => $course,
                                "campus" => $course->campuses()->first()   
                            ])
                        @endforeach


                    @endif

                </div>
            </div>
        </div>
    </section>
    <section class="section-footer-nav">
        <a href="javascript:void(0)" 
            data-step="{{$step}}"
            data-route="{{ route('quotations.show' , [
                        'school'        => $school,
                        'quotation'     => $quotation,
                        'step'          => $steps['next']['step']
            ]) }}" 
            class="btn is-flat btn-accent-1 next px-5" 
            onclick="app.isValid(this)"
            data-message="{{$steps['current']['error_message']}}"
            >{{__('Next')}}</a>
    </section>
</main>