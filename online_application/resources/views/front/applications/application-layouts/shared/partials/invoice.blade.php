@if(count(\App\Helpers\Application\PaymentHelpers::getPaymentsTypeFromApplicationWithFirstPayment($application)) > 0)
    <div class="col-md-12">
        <h2>{{__("Select a Payment Method Type")}}</h2>
    </div>
    <div class="w-100">&nbsp;</div>
    <div class="btn-group float-right" role="group">
        <div class="btn-group" role="group">

            {{--        <div class="col-md-12">--}}
            {{--            @include('back.layouts.core.forms.select',[--}}
            {{--                'name' => 'payment_type',--}}
            {{--                'label' => 'Payment Type',--}}
            {{--                'class' => 'select2',--}}
            {{--                'required' => true,--}}
            {{--                'attr' => "onchange=app.showPaymentType(this,'".route('payment.type', [$school, $application]) ."')",--}}
            {{--                'value' => (isset($submition->properties['payment_type']))? $submition->properties['payment_type'] : '',--}}
            {{--                'data' => \App\Helpers\Application\PaymentHelpers::getPaymentsTypeFromApplication($application)--}}
            {{--            ])--}}
            {{--        </div>--}}

            {{--        <div class="form-group">--}}
            {{--            @include('back.layouts.core.forms.radio-group',--}}
            {{--            [--}}
            {{--                'name'          => "payment_type",--}}
            {{--                'label'         => __("Select a Payment Method Type"),--}}
            {{--                'class'         => 'payment_type' ,--}}
            {{--                'required'      => true,--}}
            {{--                'attr'          => "onchange=app.showPaymentType(this,'".route('payment.type', [$school, $application]) ."')",--}}
            {{--                'value'         => (isset($submition->properties['payment_type']))? $submition->properties['payment_type'] : '',--}}
            {{--                'placeholder'   => __('Activities'),--}}
            {{--                'data'          => \App\Helpers\Application\PaymentHelpers::getPaymentsTypeFromApplication($application),--}}
            {{--                'deselect'      => false,--}}
            {{--            ])--}}
            {{--        </div>--}}

            @foreach (\App\Helpers\Application\PaymentHelpers::getPaymentsTypeFromApplicationWithFirstPayment($application) as $default=>$label)

                <div class="custom-control custom-radio payment_type" style="margin-top: 7px;">

                    <input class="custom-control-input" id="payment_type_{{$default}}" name="payment_type" type="radio"
                           onchange="app.showPaymentType(this, '{!! route('payment.type', [$school, $application]) !!}')"
                           value="{{$default}}">
                    <label class="custom-control-label" for="payment_type_{{$default}}">
                        {!! __($label) !!}
                    </label>
                </div>

            @endforeach

        </div>
    </div>

{{--    <div class="w-100">&nbsp;</div>--}}
{{--    <br>--}}
{{--    <div class="w-100">&nbsp;</div>--}}
{{--    <br>--}}
{{--    <div class="w-100">&nbsp;</div>--}}
{{--    <div class="w-100">&nbsp;</div>--}}
{{--    <br>--}}
{{--    <div class="w-100">&nbsp;</div>--}}


    {{--<div class="payment-type-resume">--}}
    {{--</div>--}}
@endif

<div class="btn-group float-right" role="group">
    <div class="btn-group" role="group">
        <div class="custom-control custom-radio payment_type" style="margin-top: 7px;">

            <input class="custom-control-input" id="payment_type_fee" name="payment_fee" type="radio" checked>
            <label class="custom-control-label" for="payment_type_fee">
                <h3>{{__('First payment')}}: <small>{{__('Mandatory payment')}}</small></h3>
                Frais d'administration: {{$application->properties['application_fees']}}$
            </label>
        </div>
    </div>
</div>