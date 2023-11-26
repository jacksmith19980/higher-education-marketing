@extends('back.layouts.default')

@section('content')

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-12">

                <div class="card">

                    <div class="card-body wizard-content">

                        <h4 class="card-title">{{__('Add Assistant Builder')}}</h4>

                        <form method="POST" action="{{ route('assistantsBuilder.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Save') }}"
                              data-add-button="{{__('Save')}}" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1 -->

                            <h6>{{__('General')}}</h6>
                            <section>
                                @include('back.recruitment_assistant._partials.general')

                                @foreach($sections as $section)
                                    @include('back.recruitment_assistant._partials.section-header', $section)
                                @endforeach

                                @include('back.recruitment_assistant._partials.apply')

                            </section>
                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

