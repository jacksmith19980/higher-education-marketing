<main class="main step-2">
    <section class="section-main pb-4 pb-lg-5">
        <div class="container">

            @include('front.quotations._partials.general.step-header' , ['steps' => $steps])

            <div class="loop-item-container">
                <ul class="debug"></ul>
                <div class="row justify-content-md-center program-list-container">
                    <div class="col-md-12">
                        @foreach ($cart['courses'] as $item)

                            @include('front.quotations._partials.date-addons.course-list' , [
                                'school'    => $school,
                                'quotation' => $quotation,
                                'course'    => $course = $courses->where('id' , $item['id'])->first(),
                                'courses'   => $courses,
                                'campus'    => $course->campuses->where('id' , $item['campus'])->first()
                            ])
                        @endforeach

                    </div>
                </div>
            </div>



    </section>
        @include('front.quotations._partials.prev-next' , [
            'school'    => $school,
            'quotation' => $quotation,
            'steps'     => $steps
        ])
</main>