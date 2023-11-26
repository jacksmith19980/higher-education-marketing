@extends('back.layouts.default')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{__('Settings')}}</h4>
                <h6 class="card-subtitle">{{__('Configure your settings')}}</h6>
                <div class="row">
                    <div class="col-lg-4 col-xl-3">
                        <!-- Nav tabs -->
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">




                            <a class="nav-link active"
                            id="v-pills-school-tab"
                            data-toggle="pill"
                            href="#v-pills-school"
                                role="tab" aria-controls="v-pills-school" aria-selected="true">{{__('School')}}</a>

                            <a class="nav-link" id="v-pills-education-tab" data-toggle="pill" href="#v-pills-education"
                                role="tab" aria-controls="v-pills-education" aria-selected="true">{{__('Education')}}</a>

                            <a class="nav-link" id="v-pills-branding-tab" data-toggle="pill" href="#v-pills-branding"
                                role="tab" aria-controls="v-pills-branding" aria-selected="true">{{__('Branding')}}</a>

                            <a class="nav-link" id="v-pills-login-register-tab" data-toggle="pill"
                                href="#v-pills-login-register" role="tab" aria-controls="v-pills-login-register"
                                aria-selected="false">{{__('Login and Register')}}</a>


                            <a class="nav-link" id="v-pills-admissions-tab" data-toggle="pill"
                                href="#v-pills-admissions" role="tab" aria-controls="v-pills-admissions"
                                aria-selected="false">{{__('CallBack Request')}}</a>

                            @features(['virtual_assistant'])
                                <a class="nav-link" id="v-pills-calendar-tab"
                                data-toggle="pill" href="#v-pills-calendar"
                                    role="tab"
                                    aria-controls="v-pills-calendar" aria-selected="false">{{__('Calendar &
                                    Schedule')}}</a>
                            @endfeatures

                            {{-- <a class="nav-link" id="v-pills-tax-tab" data-toggle="pill" href="#v-pills-tax"
                                role="tab" aria-controls="v-pills-tax" aria-selected="false">{{__('Finance &
                                Tax')}}</a>--}}

                            @features(['agency'])
                            <a class="nav-link" id="v-pills-agencies-tab" data-toggle="pill" href="#v-pills-agencies"
                                role="tab" aria-controls="v-pills-agencies" aria-selected="false">{{__('Recruiters
                                Hub')}}</a>
                            @endfeatures

                            {{-- <a class="nav-link" id="v-pills-applications-tab" data-toggle="pill"
                                href="#v-pills-applications" role="tab" aria-controls="v-pills-applications"
                                aria-selected="false">{{__('Applications')}}</a>--}}

                            @features(['quote_builder'])
                            <a class="nav-link" id="v-pills-quotation-tab" data-toggle="pill" href="#v-pills-quotation"
                                role="tab" aria-controls="v-pills-quotation" aria-selected="false">{{__('Quote
                                Builder')}}</a>
                            @endfeatures

                            @features(['quote_builder'])
                            <a class="nav-link" id="v-pills-stages-tab" data-toggle="pill" href="#v-pills-stages"
                                role="tab" aria-controls="v-pills-stages" aria-selected="false">{{__('Stages')}}</a>
                            @endfeatures

                            <a class="nav-link" id="v-pills-tracking-tab" data-toggle="pill" href="#v-pills-tracking"
                                role="tab" aria-controls="v-pills-tracking" aria-selected="false">{{__('Tracking')}}</a>




                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-9">
                        <div class="tab-content" id="v-pills-tabContent">




                            <div class="tab-pane fade active show" id="v-pills-school" role="tabpanel"
                                aria-labelledby="v-pills-school-tab">@include('back.settings.school-settings')</div>


                            <div class="tab-pane fade" id="v-pills-education" role="tabpanel"
                                aria-labelledby="v-pills-education-tab">
                                @include('back.settings.education-settings')
                            </div>


                            <div class="tab-pane fade" id="v-pills-branding" role="tabpanel"
                                aria-labelledby="v-pills-branding-tab">@include('back.settings.brand-settings')</div>




                            <div class="tab-pane fade" id="v-pills-admissions" role="tabpanel"
                                aria-labelledby="v-pills-admissions-tab">@include('back.settings.admissions-settings')
                            </div>


                            <div class="tab-pane fade" id="v-pills-login-register" role="tabpanel"
                                aria-labelledby="v-pills-login-register-tab">@include('back.settings.auth-settings')
                            </div>

                            {{-- <div class="tab-pane fade" id="v-pills-applications" role="tabpanel"
                                aria-labelledby="v-pills-applications-tab">
                                @include('back.settings.applications-settings')</div>--}}

                            <div class="tab-pane fade" id="v-pills-quotation" role="tabpanel"
                                aria-labelledby="v-pills-quotation-tab">@include('back.settings.quotation-settings')
                            </div>

                             <div class="tab-pane fade" id="v-pills-calendar" role="tabpanel"
                                aria-labelledby="v-pills-calendar-tab">
                                @include('back.settings.calendar-settings')
                                </div>

                            <div class="tab-pane fade" id="v-pills-agencies" role="tabpanel"
                                aria-labelledby="v-pills-agencies-tab">@include('back.settings.agencies-settings')</div>

                            <div class="tab-pane fade" id="v-pills-stages" role="tabpanel"
                                aria-labelledby="v-pills-stages-tab">@include('back.settings.stages-settings')</div>



                            <div class="tab-pane fade" id="v-pills-tracking" role="tabpanel"
                                aria-labelledby="v-pills-tracking-tab">@include('back.settings.tracking-settings')</div>




                            {{-- <div class="tab-pane fade" id="v-pills-tax" role="tabpanel"
                                aria-labelledby="v-pills-tax-tab">@include('back.settings.tax-settings')</div>--}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
