<main class="main step-2">
    <section class="section-main pb-4 pb-lg-5">
        <div class="container">
            <div class="loop-item-container">
                <div class="row justify-content-md-center">
                    <div class="col-12">&nbsp;</div>
                    <div class="col-sm-12 col-md-6">

                        <div class="subTotal-container pr-sm-4 program-list-container">

                            @include('front.recruitment_assistant._partials.apply.program')

                            <div class="subTotal-container-border"></div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="total-container pl-md-4">
                         <div class="button-container form-toggle-btngroup text-left pt-4">
                            <button type="button" data-form="sendToEmailFormWrapper"
                                    class="btn is-flat btn-outline-accent-1 mr-3 toggle-form">
                                {{__('Receive Summary')}}
                            </button>
                               {{--  @if ($user = auth()->guard('student')->user()) --}}

                                    <button type="button" data-form="bookNowFormWrapper"
                                                class="btn is-flat btn-accent-1 toggle-form">
                                            {{__('Apply now')}}
                                            <i class="custom-icon icon-shield ml-2 d-inline-block"></i>
                                        </button>

                                {{-- @endif --}}
                            </div>

                                <div class="form-toggle py-4">
                                    <div class="form-wrapper sendToEmailFormWrapper" id="sendToEmailFormWrapper">
                                        @include('front.recruitment_assistant._partials.forms.send-via-email',
                                        ['assistantBuilder' => $assistantBuilder])
                                    </div>

                                    <div class="form-wrapper bookNowFormWrapper" id="bookNowFormWrapper">
                                        @include('front.recruitment_assistant._partials.forms.register-form',
                                        ['assistantBuilder' => $assistantBuilder])

                                        @include('front.recruitment_assistant._partials.forms.login-form',
                                        ['assistantBuilder' => $assistantBuilder])


                                    </div>
                                </div>
                            <!-- </ul> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>