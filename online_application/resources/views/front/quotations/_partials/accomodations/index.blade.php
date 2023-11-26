<main class="main step-1">
    <section class="section-main pb-4 pb-lg-5">
        <div class="container">

             @include('front.quotations._partials.general.step-header' , ['steps' => $steps])

             <div class="loop-item-container">
                <div class="row justify-content-md-center">
                    <!-- Loop Items-->
                    @if ( isset($quotation->properties['accommodation_options']) &&  isset($quotation->properties['accommodation_options_price']))
                    <div class="col-12" style="margin-bottom: 15px;"><strong>Pricing Template:</strong> {{ucfirst($quotation->properties['accommodation_cost_template'])}}</div><br/>
                        @foreach ($quotation->properties['accommodation_options'] as $key => $option)
                            @include('front.quotations._partials.accomodations.option' , [
                                'option' => $option,
                                'price'  => $quotation->properties['accommodation_options_price'][$key],
                                'key'    => $key
                            ])
                        @endforeach
                    @endif
                </div>
            </div>

    </section>
    @include('front.quotations._partials.prev-next' , [
    'school' => $school,
    'quotation' => $quotation,
    'steps' => $steps
    ])
</main>