@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{ __('Add Pricing Schema') }}</h4>
                        <hr>
                        <form method="POST" action="{{ route('pricing.update', $pricing->id) }}"
                            class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update') }}"
                            data-add-button="{{ __('Update') }}">
                            @method('PUT')
                            @csrf
                            <h6>{{ __('Information') }}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'title',
                                            'label' => 'Title',
                                            'class' => '',
                                            'required' => true,
                                            'attr' => '',
                                            'value' => $pricing->title,
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.switch', [
                                            'name' => 'active',
                                            'label' => __('Active'),
                                            'class' => 'switch ajax-form-field',
                                            'required' => true,
                                            'attr' => 'data-on-text=Yes data-off-text=No',
                                            'helper_text' => '',
                                            'value' => $pricing->is_active,
                                            'default' => true,
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="description">{{ __('Description') }}<span
                                                class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control mb-2" id="description" required rows="4">{{ $pricing->description }}</textarea>
                                    </div>
                                </div>

                                <div class="row" id="pricings_wrapper">
                                    @php
                                        $details = json_decode($pricing->properties);
                                        $i = 1;
                                    @endphp

                                    @foreach ($details as $detail)
                                        <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

                                            <div class="col-md-3">
                                                @include('back.layouts.core.forms.number', [
                                                    'name' => 'start_date_' . $i,
                                                    'label' => 'From Week',
                                                    'class' => '',
                                                    'required' => true,
                                                    'attr' => 'min=1 max=53',
                                                    'autocomplete=off onfocusout=verifyPricingDates(event)',
                                                    'value' => $detail->startDate,
                                                    'data' => '',
                                                ])
                                            </div>

                                            <div class="col-md-3">
                                                @include('back.layouts.core.forms.number', [
                                                    'name' => 'end_date_' . $i,
                                                    'label' => 'To Week',
                                                    'class' => '',
                                                    'required' => true,
                                                    'attr' => 'min=1 max=53',
                                                    'autocomplete=off onfocusout=verifyPricingDates(event)',
                                                    'value' => $detail->endDate,
                                                    'data' => '',
                                                ])
                                            </div>

                                            <div class="col-md-4">
                                                @include('back.layouts.core.forms.text-input', [
                                                    'name' => 'value_' . $i,
                                                    'label' => 'Value',
                                                    'class' => '',
                                                    'required' => true,
                                                    'attr' => 'autocomplete=off',
                                                    'value' => $detail->value,
                                                    'data' => '',
                                                ])
                                            </div>

                                            <div class="col-md-1 action_wrapper">
                                                <div class="form-group m-t-27">
                                                    <button class="btn btn-danger" type="button"
                                                        onclick="app.deleteElementsRow(this, 'date-row')">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </div>
                                <input type="hidden" id="current_id" name="current_id" value="{{$i}}">
                                <div class="row">
                                    <div class="col-md-2 offset-10 m-b-30">
                                        @include('back.layouts.core.helpers.add-elements-button', [
                                            'text' => 'Add Dates',
                                            'action' => 'pricingSchema.getPricingRow',
                                            'container' => '#pricings_wrapper',
                                        ])
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
