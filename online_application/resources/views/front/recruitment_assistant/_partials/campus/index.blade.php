<main class="main step-1">
    <section class="section-main pb-4 pt-4 pb-lg-5">
        <div class="container">

          {{--   @include('front.quotations._partials.general.step-header' , ['steps' => $steps]) --}}


            <div class="alert alert-danger {{$step}}" style="display: none">
                {{__($steps['current']['error_message'])}}
            </div>

            <div class="loop-item-container">
             

                <div class="row justify-content-md-center">


                    @foreach ($campuses as $campus)
                        @include('front.recruitment_assistant._partials.campus.campus' , [
                        "camnpus"  => $campus
                        ])
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    <section class="section-footer-nav">
        <a href="javascript:void(0)"
           data-step="{{$step}}"
           data-route="{{ route('assistants.show' , [
                        'school'        => $school,
                        'assistantBuilder'     => $assistantBuilder,
                        'step'          => $steps['next']['step']
            ]) }}"
           class="btn is-flat btn-accent-1 next px-5"
           onclick="app.isValid(this)"
           data-message="{{$steps['current']['error_message']}}"
        >{{__('Next')}}</a>
    </section>
</main>