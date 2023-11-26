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
                            @foreach ($cart['courses'] as $course)
                                @include('front.quotations._partials.quote.course' , [
                                'course' => $course,
                                'campus' => $campuses->where('id' , $course['campus'])->first()
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

                                <li>{{__('Program Sub-total')}}
                                    : {{$settings['school']['default_currency']}} {{$subTotal}}</li>


                                @if (isset($price['addons']) && $price['addons']!= 0)
                                    <li>{{__('Extra Activity Sub-total')}}
                                        : {{$settings['school']['default_currency']}} {{$price['addons']}}</li>
                                @endif

                                @if ( isset($price['accomodations']) && $price['accomodations'] != 0 )
                                    <li>{{__('Accommodation Sub-total')}}
                                        : {{$settings['school']['default_currency']}} {{$price['accomodations']}}</li>
                                @endif

                                @if (isset($price['transfer']) && $price['transfer'] != 0)
                                    <li>{{__('Transfer Sub-total')}}
                                        : {{$settings['school']['default_currency']}} {{$price['transfer']}}</li>
                                @endif

                                @if (isset($price['miscellaneous']) && $price['miscellaneous'] != 0)
                                    <li>{{__('Miscellaneous Sub-total')}}
                                        : {{$settings['school']['default_currency']}} {{$price['miscellaneous']}}</li>
                                @endif

                                @if (isset($price['discount']) && $price['discount'] != 0)
                                    <div id="price" class="row pt-2">
                                            <div class="col-7">
                                                <h2>
                                                    {{$settings['school']['default_currency']}}
                                                    <span id="total">{{$price['total']}}</span>
                                                    /{{__('person')}}
                                                </h2>
                                            </div>
                                            <div class="col-3">
                                                <h5>
                                                <s class="text-muted">
                                                    {{$settings['school']['default_currency']}}
                                                    <span id="total_before_discount">{{$price['total_before_discount']}}</span>
                                                </s>
                                                </h5>
                                            </div>
                                    </div>
                                @else
                                    <div id="price" class="row pt-2">
                                        <div class="col-12 text-left">
                                            <h4>
                                                <span id="total">
                                                {{$price['total']}} {{$settings['school']['default_currency']}}</span>
                                                /{{__('person')}}
                                            </h4>
                                        </div>
                                        <div id="total_container" class="col-3 hidden">
                                            <h5>
                                                <s class="text-muted">
                                                    {{$settings['school']['default_currency']}}
                                                    <span id="total_before_discount">{{$price['total']}}</span>
                                                </s>
                                            </h5>
                                        </div>
                                    </div>
                                @endif
                                @if ($promos)
                                <!-- Promo code -->
                                    <div id="promo-container-link" class="row">
                                        <div class="col-md-4 input-group">
                                            <a href="javascript:void(0)" onclick=app.showPromocodeInput()>{{__('Apply Promo Code')}}</a>
                                        </div>
                                    </div>
                                    <div id="promo-container-input" class="row hidden">
                                        <div id="promo-inputs" class="col-md-6 input-group">
                                            <input class="form-control form-control-sm" type="text" name="promocode" id="promocode" placeholder="Promo-Code">
                                            <div class="input-group-append">
                                                <button id="promocode" class="btn-sm btn-outline-secondary"
                                                        onclick=app.applyPromocode("{{route('promocodes.apply')}}")
                                                >{{__('Apply')}}</button>
                                            </div>

                                        </div>
                                        <div class="col-md-6 input-group">
                                            <small id="promocode-help" class="form-text hidden"></small>
                                        </div>
                                    </div>
                                <!-- Promo code END -->
                                @endif
                                <div class="button-container form-toggle-btngroup text-left pt-4">

                                    <button type="button" data-form="sendToEmailFormWrapper"
                                            class="btn is-flat btn-outline-accent-1 mr-3 toggle-form">
                                        {{__('Send quote via email')}}
                                    </button>

                                    @if ($user = auth()->guard('student')->user())

                                        <a href="javascript:void(0)" class="btn is-flat btn-accent-1 text-white"
                                           onclick="event.preventDefault(); document.getElementById('createBooking').submit();">
                                            {{__('Book now')}}
                                            <i class="custom-icon icon-shield ml-2 d-inline-block"></i>
                                        </a>

                                        <form action="{{ route('quotations.login' , ['school'=> $school , 'quotation' => $quotation]) }}"
                                                method="POST" id="createBooking">
                                            @csrf
                                            <input type="hidden" value="{{json_encode($cart)}}" name="cart">
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
                                    <div class="form-wrapper sendToEmailFormWrapper" id="sendToEmailFormWrapper">
                                        @include('front.quotations._partials.forms.send-via-email',
                                        ['quotation' => $quotation])
                                    </div>

                                    <div class="form-wrapper bookNowFormWrapper" id="bookNowFormWrapper">

                                        @include('front.quotations._partials.forms.login-form',
                                        ['quotation' => $quotation]) @include('front.quotations._partials.forms.register-form',
                                        ['quotation' => $quotation])

                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
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
