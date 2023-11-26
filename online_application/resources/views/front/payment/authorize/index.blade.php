@extends('front.layouts.payment')
@section('content')
<div class="page-wrapper" style="padding-top:30px;display:block">
    <div class="container-fluid">
        <div class="row">

@if (!isset($properties))

  @php
    $properties = $field->payment->properties;
  @endphp

@endif


@php
    $invoiceStatus = $invoice->status->last();
@endphp

@if ( isset($invoice) &&  $invoiceStatus->status !='Paid')
<div class="container mt-5 payment-form"  {{ ($properties['disable_submission']) ? 'data-submit-button' : ''  }}  >
	<div class="row">
	<div class="col-md-10 offset-md-1">

        <div class="card">
            <div class="card-body">
                @if (isset($properties['before_payment_text']))
                    <div class="col-md-10 offset-md-1" style="margin-bottom:20px;">
                        {!! __($properties['before_payment_text'])  !!}
                    </div>
                @endif


                <div class="clear"></div>
                <div class="col-md-6 offset-md-3">

                    <div class="errors_wrapper">
                        @if($errors->any())
                            <div class="alert alert-danger">{{$errors->first()}}</div>
                        @endif
                    </div>

                    @csrf
                    <input type="hidden" name="payment" class="cc_info" data-name="payment" value="authorize" />

                    <div class="col-md-12">
                        @include('back.layouts.core.forms.text-input',
                        [
                            'name'      => 'cc_number_'.time(),
                            'label'     => 'Card Number' ,
                            'class'     => 'cc_info' ,
                            'required'  => true,
                            'attr'      => 'data-mask=cc_number data-name=cc_number',
                            'value'     => ''
                        ])
                    </div>

                    <div class="col-md-12">
                        @include('back.layouts.core.forms.text-input',
                        [
                            'name'      => 'cc_name_'.time(),
                            'label'     => 'Card Holder Name' ,
                            'class'     => 'cc_info' ,
                            'required'  => true,
                            'attr'      => 'data-name=cc_name',
                            'value'     => ''
                            ])
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="padding:0 19px;">
                            @include('back.layouts.core.forms.text-input',
                            [
                                'name'          => 'cc_expiry_'.time(),
                                'label'         => 'Card Expiry Date' ,
                                'class'         => 'cc_info' ,
                                'required'      => true,
                                'attr'          => 'data-mask=cc_expiry_YYYY_MM data-name=cc_expiry',
                                'value'         => '',
                                'helper'        => 'YYYY/MM'
                                ])
                        </div>
                        <div class="col-md-6" style="padding:0 19px;">
                            @include('back.layouts.core.forms.text-input',
                            [
                                'name'      => 'cc_cvs_'.time(),
                                'label'     => 'CVS' ,
                                'class'     => 'cc_info' ,
                                'required'  => true,
                                'attr'      => 'data-name=cc_cvs',
                                'value'     => ''
                            ])
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button disabled style="margin-top:15px;" class="btn btn-success btn-block payment-btn btn-large" onclick="app.proccessPayment(this, 'authorize' , '{{ route('application.payment.pay.no-login' , ['school' => $school , 'application' => $invoice->application , 'invoice' => $invoice ]) }}' )">PAY</button>
                    </div>
                </div>

	        </div>
	    </div>
	    </div>
	</div>
</div>
@endif

@if ( $invoiceStatus->status =='Paid')
<div class="container mt-5">
	<div class="row">
        <div class="list-group-item list-group-item-action flex-column align-items-center w-50" style="margin:5px auto;">
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
        </div>
    </div>
</div>
@endsection
