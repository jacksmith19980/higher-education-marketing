@if (!isset($properties))
@php
$properties = $field->payment->properties;
@endphp
@endif
@php
$invoiceStatus = $invoice->status->last();
@endphp
@if ( isset($invoice) && $invoiceStatus->status !='Paid')
<div class="container-fluid mt-4 payment-form" {{ ($properties['disable_submission']) ? 'data-submit-button' : '' }}>
    <h4 class="text-center">{{__('Your Invoice is ready. Please make a payment below')}}</h4>
    <div class="row mt-4 card-info-wrapper">
        <div class="col-lg-5 card-info">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" style="background:none;border:1px solid #CCCCCC;border-bottom:2px solid white"
                        data-toggle="tab" href="#credit_card" role="tab">
                        <span class="hidden-xs-down">
                            {{__('Credit Card')}}
                        </span>
                    </a>
                </li>
            </ul>
            <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="credit_card" role="tabpanel">
                    <div class="p-20">
                        <div>

                            <div class="errors_wrapper">
                                @if($errors->any())
                                {{$errors->first()}}
                                @endif
                            </div>

                            @csrf
                            <input type="hidden" name="payment" class="cc_info" data-name="payment" value="moneris" />

                            <div class="col-md-12">
                                @include('back.layouts.core.forms.text-input',
                                [
                                'name' => 'cc_number_'.time(),
                                'label' => 'Card Number',
                                'hint_before' => '<i class="fas fa-credit-card"></i>',
                                'class' => 'cc_info' ,
                                'required' => true,
                                'attr' => 'data-mask=cc_number data-name=cc_number',
                                'value' => ''
                                ])
                            </div>

                            <div class="col-md-12">
                                @include('back.layouts.core.forms.text-input',
                                [
                                'name' => 'cc_name_'.time(),
                                'label' => 'Card Holder Name' ,
                                'class' => 'cc_info' ,
                                'required' => true,
                                'attr' => 'data-name=cc_name',
                                'value' => ''
                                ])
                            </div>
                            <div class="row">
                                <div class="col-lg-6" style="padding:0 19px;">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'cc_expiry_'.time(),
                                    'label' => 'Expiration Date' ,
                                    'class' => 'cc_info' ,
                                    'placeholder' => 'MM/YY',
                                    'required' => true,
                                    'attr' => 'data-mask=cc_expiry data-name=cc_expiry',
                                    'value' => '',
                                    'helper' => 'MM/YY'
                                    ])
                                </div>
                                <div class="col-lg-6" style="padding:0 19px;">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'cc_cvs_'.time(),
                                    'label' => 'CV Code' ,
                                    'placeholder' => 'CVC',
                                    'class' => 'cc_info' ,
                                    'required' => true,
                                    'attr' => 'data-name=cc_cvs',
                                    'value' => ''
                                    ])
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button style="margin-top:15px;" class="btn btn-secondary payment-btn"
                                    onclick="app.proccessPayment(this, 'moeris' , '{{ route('application.payment.pay.no-login' , ['school' => $school , 'application' => $application , 'invoice' => $invoice ]) }}' )">{{__('MAKE
                                    PAYMENT')}}</button>
                            </div>
                        </div>



                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-1"></div>

        <div class="col-lg-6 purchase-summary">
            <h5 class="mb-3">{{__('Purchase Summary')}}</h5>


            <div class="table-responsive">


                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">
                                <strong>{{__('Product Info')}}</strong>
                            </th>
                            <th scope="col">
                                <strong>{{__('Price')}}</strong>
                            </th>
                            <th scope="col">
                                <strong>{{__('Quantity')}}</strong>
                            </th>
                            <th scope="col">
                                <strong>{{__('Total')}}</strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($properties['product']))
                        @foreach ($properties['product'] as $product)
                        <tr>
                            <th scope="row">
                                {{$product['title']}}
                            </th>
                            <td>{{$product['price']}} {{$settings['school']['default_currency']}}</td>
                            <td>{{$product['quantity']}}</td>
                            <td>{{ (int) $product['price'] * (int)
                                $product['quantity'] }} {{$settings['school']['default_currency']}}</td>
                        </tr>
                        @endforeach
                        @else

                        <tr>
                            <th scope="row">
                                {{__('Registration Fees')}}
                            </th>
                            <td>{{$invoice->total}} {{$settings['school']['default_currency']}}</td>
                            <td>1</td>
                            <td>{{$invoice->total}} {{$settings['school']['default_currency']}}</td>
                        </tr>
                        @endif
                    </tbody>

                    <tfoot style="border-top:2px solid #CCCCCC">
                        <tr>
                            <th scope="row" colspan="3">
                                <strong>{{__('Cart Total')}}</strong>
                            </th>
                            <th scope="row">
                                <strong>{{$invoice->total}}
                                    {{$settings['school']['default_currency']}}
                                </strong>
                            </th>
                        </tr>
                    </tfoot>
                </table>

				@if (isset($properties['before_payment_text']))
                <div class="mt-5">
                    {!! __($properties['before_payment_text']) !!}
                </div>
                @endif

            </div>



        </div>



    </div>
</div>
@endif

@if ( $invoiceStatus->status =='Paid')
<div class="container mt-5">
    <div class="row">
        <div class="list-group-item list-group-item-action flex-column align-items-center w-50"
            style="margin:5px auto;">
            <div class="d-flex justify-content-between">
                <h5 class="mb-1">
                    <span class="d-block">
                        <i class="fa fa-circle text-success" style="font-size:50%"></i>
                        {{$invoiceStatus->status}}
                    </span>
                    <small class="text-muted">{{$invoiceStatus->created_at->diffForHumans()}}</small>
                </h5>
            </div>

            {{-- @foreach (PaymentHelpers::getInvoiceStatusProperties($invoiceStatus->properties) as $key=>$value)
            <small class="text-muted d-block"><strong>{{$key}}: </strong> {{$value}}</small>
            @endforeach --}}
        </div>
    </div>
</div>
@endif
