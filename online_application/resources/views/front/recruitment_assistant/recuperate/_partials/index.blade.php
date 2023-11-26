<main class="main step-2">
    <section class="section-main pb-4 pb-lg-5">
        <div class="container">
            <div class="header-main-content pt-4 pb-4 pb-md-4 pb-lg-4 pb-lg-3">
                <h3 class="text-left">{{__('Your VAA Summary')}}</h3>
            </div>
            <div class="loop-item-container">
                <div class="row justify-content-md-center">

                    <div class="col-sm-12 col-md-6">

                        <div class="subTotal-container pr-sm-4 program-list-container">

                            <div class="program-list-item mb-4">
                                <div class="card">
                                    <div class="card-header vaa-summary">
                                        <p class="text-capitalize text-left" style="color: #fff;">
                                            {{__('YOUR PROGRAM SUMMARY')}}
                                        </p>
                                    </div>
                                    <div class="card-body">
                                       @php  //dd($assistant->properties); @endphp
                                        <ul class="fa-ul">
                                            <li><span class=" fa-li text-muted"><i class="fas fas fa-graduation-cap"></i></span>
                                                <h5 class="text-left program-title">{{$assistant_properties['programs'][0]['title']}}</h5>

                                                <p class="text-justify program-text">
                                                    {{  strip_tags(substr($assistant_properties['programs'][0]['details'], 0,  300)) }}
                                                </p>
                                            </li>
                                            <br>
                                            <li><span class=" fa-li text-muted text-muted"><i class="fas fa-map-marker-alt  text-muted"></i></span>
                                            <h5 class="text-left program-title">{{$assistant_properties['campuses'][0]['title']}} {{__('Campus')}}</h5>
                                                <p class="text-justify program-text">
                                                    @isset($assistant_properties['campuses'][0]['location'])
                                                        {{$assistant_properties['campuses'][0]['location']}}
                                                    @endisset
                                                </p>
                                            </li>
                                            <br>
                                            <li><span class=" fa-li text-muted"><i class="fas  fas fa-calendar-alt"></i></span>
                                                <h5 class="text-left program-title">
                                                    {{ QuotationHelpers::formatDate($assistant_properties['programs'][0]['start'], 'd M, Y') }} - {{ QuotationHelpers::formatDate($assistant_properties['programs'][0]['end'], 'd M, Y') }}
                                                    ( {{ \App\Helpers\Assistant\AssistantHelpers::getSchedule($assistant_properties['programs'][0]['schudel']) }})
                                                </h5>
                                            </li>
                                            @if(isset($assistant_properties['financials']) && count($assistant_properties['financials']) > 0)
                                                <br>
                                                <li><span class=" fa-li text-muted"><i class="far fa-money-bill-alt"></i></span>
                                                    <h5 class="text-left program-title">
                                                        {{__('Financial Aid')}}
                                                    </h5>
                                                    @foreach($assistant_properties['financials'] as $financial)
                                                        <p class="text-justify program-text">
                                                        {{$financial}}
                                                        </p>
                                                    @endforeach
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="subTotal-container-border"></div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="total-container pl-md-4">
                            <ul class="quote-total">



                                <div class="button-container form-toggle-btngroup text-left pt-4">
                                    @if ($user = auth()->guard('student')->user())

                                        <a href="javascript:void(0)" class="btn is-flat btn-accent-1 text-white"
                                           onclick="event.preventDefault(); document.getElementById('createBooking').submit();">
                                            {{__('Apply now')}}
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
                                            {{__('Apply now')}}
                                            <i class="custom-icon icon-shield ml-2 d-inline-block"></i>
                                        </button>

                                    @endif
                                </div>

                                <div class="form-toggle py-4">
                                    <div class="form-wrapper bookNowFormWrapper" id="bookNowFormWrapper">
                                       @include('front.recruitment_assistant.recuperate._partials.forms.register-form', ['assistantBuilder' => $assistant_builder])
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>