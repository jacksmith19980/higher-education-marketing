@extends('back.layouts.default')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body wizard-content">

                     <h4 class="card-title">{{__('Add Quote Builder')}}</h4>

                    <form method="POST" action="{{ route('quotations.store') }}" class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Save') }}" data-add-button="{{__('Save')}}" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1 -->

                        <h6>{{__('General')}}</h6>
                        <section>
                             @include('back.quotation._partials.general')
                        </section>

                        <h6>{{__('Accomodation')}}</h6>

                        <section>
                            @include('back.quotation._partials.accomodation')
                        </section>

                        <h6>{{__('Transfer')}}</h6>

                        <section>
                            @include('back.quotation._partials.transfer')
                        </section>

                        <h6>{{__('Miscellaneous')}}</h6>

                        <section>
                            @include('back.quotation._partials.miscellaneous.miscellaneous')
                        </section>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

