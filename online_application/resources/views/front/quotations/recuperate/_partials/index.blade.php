<main class="main step-2">
    <section class="section-main pb-4 pb-lg-5">
        <div class="container">
            <div class="header-main-content pt-4 pb-4 pb-md-4 pb-lg-4 pb-lg-3">
                <h3 class="text-left">{{__('Your Quote')}}</h3>
            </div>
            <div class="loop-item-container">
                <div class="row justify-content-md-center">

                    <div class="col-sm-12 col-md-6">

                        <div class="subTotal-container pr-sm-4 program-list-container">

                            @php
                                $subTotal = 0
                            @endphp
                            @foreach ($booking->invoice['details']['courses'] as $course)
                                @include('front.quotations._partials.quote.course' , [
                                'course' => $course,
                                'campus' => App\Tenant\Models\Campus::where('id' , $course['campus'])->first()
                                ])
                                @php
                                    $subTotal += $course['total']
                                @endphp
                            @endforeach

                            <div class="subTotal-container-border"></div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="total-container pl-md-4">
                            <ul class="quote-total">

                                @include("front.quotations.recuperate._partials.subTotal", [
                                        'label' => __('Program Sub-total'),
                                        'value' => $subTotal
                                    ])


                                @if (isset($booking->invoice['addons']) && $booking->invoice['addons']!= 0)
                                    @include("front.quotations.recuperate._partials.subTotal", [
                                        'label' => __('Extra Activity Sub-total'),
                                        'value' => $booking->invoice['addons']
                                    ])
                                @endif

                                @if ( isset($booking->invoice['accommodation']) && $booking->invoice['accommodation'] != 0 )
                                    @include("front.quotations.recuperate._partials.subTotal", [
                                        'label' => __('Accommodation Sub-total'),
                                        'value' => $booking->invoice['accommodation']
                                    ])
                                @endif

                                @if (isset($booking->invoice['transfer']) && $booking->invoice['transfer'] != 0)
                                    @include("front.quotations.recuperate._partials.subTotal", [
                                        'label' => __('Transfer Sub-total'),
                                        'value' => $booking->invoice['transfer']
                                    ])
                                @endif

                                @if (isset($booking->invoice['details']['price']['miscellaneous']) && $booking->invoice['details']['price']['miscellaneous'] != 0)
                                    @include("front.quotations.recuperate._partials.subTotal", [
                                        'label' => __('Miscellaneous Sub-total'),
                                        'value' => $booking->invoice['details']['price']['miscellaneous']
                                    ])
                                @endif

                                <h3>{{__('YOUR PRICE')}}:
                                    <span>{{$settings['school']['default_currency']}}{{$booking->invoice['totalPrice']}}/{{__('person')}}</span>
                                </h3>


                                <div class="button-container form-toggle-btngroup text-left pt-4">
                                    @if ($user = auth()->guard('student')->user())

                                        <a href="javascript:void(0)" class="btn is-flat btn-accent-1 text-white"
                                           onclick="event.preventDefault(); document.getElementById('createBooking').submit();">
                                            {{__('Book now')}}
                                            <i class="custom-icon icon-shield ml-2 d-inline-block"></i>
                                        </a>

                                        <form
                                                action="{{ route('quotations.login' , ['school'=> $school , 'quotation' => $quotation]) }}"
                                                method="POST" id="createBooking">
                                            @csrf
{{--                                            <input type="hidden" value="{{json_encode($cart)}}" name="cart">--}}
                                        </form>

                                    @else

                                        <button type="button" data-form="bookNowFormWrapper"
                                                class="btn is-flat btn-accent-1 toggle-form">
                                            {{__('Book now')}}
                                            <i class="custom-icon icon-shield ml-2 d-inline-block"></i>
                                        </button>

                                    @endif
                                </div>

                                <div class="form-toggle py-4">
                                    <div class="form-wrapper bookNowFormWrapper" id="bookNowFormWrapper">
                                        @include('front.quotations.recuperate._partials.forms.register-form',
                                        ['quotation' => $quotation])
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
