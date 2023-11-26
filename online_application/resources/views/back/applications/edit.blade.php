@extends('back.layouts.default')



@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body wizard-content">

                    <h4 class="card-title">{{__('Edit Application')}}</h4>

                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->



                    <form method="POST" action="{{ route('applications.update' , $application) }}" class="validation-wizard wizard-circle m-t-40 needs-validation" aria-label="{{ __('Update Application') }}" data-add-button="Update Application" >



                        @csrf

                        {{ method_field('PUT') }}



                        <!-- Step 1 -->

                        <h6>{{__("General")}}</h6>

                        <section>
                             @include('back.applications._partials.application-general' , ['application' => $application ])
                        </section>

                        <h6>{{__("Payment")}}</h6>
                        <section>
                            @include('back.applications._partials.application-payment' , ['application' => $application ])
                        </section>

                        <!-- Step 2 -->
                        <h6>{{__("Customization")}}</h6>
                        <section>
                            <div class="row loadCustomization">
                                @include('back.applications._partials.application-customization' , ['customization' => $customization , 'applicationCustomization' => $application->properties])
                            </div>
                        </section>


                        <h6>{{__("Instructions")}}</h6>
                        <section>
                            <div class="row loadCustomization">
                                @include('back.applications._partials.application-instructions' , ['application' => $application ])
                            </div>
                        </section>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
