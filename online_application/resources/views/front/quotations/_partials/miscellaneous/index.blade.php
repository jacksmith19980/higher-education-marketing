<main class="main step-1">
    <section class="section-main pb-4 pb-lg-5">
        <div class="container">

            @include('front.quotations._partials.general.step-header' , ['steps' => $steps])


            <div class="loop-item-container">
                <div class="row justify-content-md-center">
                    <!-- Loop Items-->
                    @if ( isset($quotation->properties['misc_options']) &&  isset($quotation->properties['misc_options_price']))
                        @foreach ($quotation->properties['misc_options'] as $key => $option)
                            @include('front.quotations._partials.miscellaneous.option' , [
                                'option' => $option,
                                'price'  => $quotation->properties['misc_options_price'][$key],
                                'key'    => $key
                            ])
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </section>
    @include('front.quotations._partials.prev-next' , [
    'school' => $school,
    'quotation' => $quotation,
    'steps' => $steps
    ])
</main>