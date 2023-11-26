@extends('back.layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">

                            <span id="payment_text">{{__('Receive Payment')}}</span>
                            @if (isset($invoice_payment))
                                <span id="invoice_text_id">{{$invoice_payment->uid}}</span>
                            @else
                                <span id="invoice_text_id"></span>
                            @endif
                        </h4>
                        <hr>

                        @include('back.layouts.core.forms.hidden-input',
                                [
                                    'name'          => 'payment_id',
                                    'label'         => '',
                                    'class'         => '' ,
                                    'required'      => false,
                                    'attr'          => '',
                                    'data'          => '',
                                    'value'         => isset($invoice_payment) ? $invoice_payment->id : '',
                                ])

                        {{-- customer, email, balance--}}
                        <div class="row">
                            <div class="col">
                                @include('back.layouts.core.forms.select',
                                [
                                    'name'          => 'customer',
                                    'label'         => 'Student',
                                    'class'         => 'select2' ,
                                    'required'      => true,
                                    'attr'          => 'onchange=app.getCustomerPaymentInfo("'.route('students.showPaymentJson').'")',
                                    'data'          => $students,
                                    'value'         => isset($student) && ($student != null) ? $student->id : '',
                                    'placeholder'   => 'Select a student'
                                ])
                            </div>

                            <div class="col-4">
                                @include('back.layouts.core.forms.text-input',
                                [
                                    'name'      => 'customer_email',
                                    'label'     => 'Student Email',
                                    'class'     => 'ajax-form-field',
                                    'required'  => false,
                                    'attr'      => 'disabled',
                                    'data'      => '',
                                    'value'     => isset($student) && ($student != null) ? $student->email : '',
                                ])
                            </div>

                            <div class="col-2">
                                {{__('Amount Received')}}: <br>
                                <span id="amount-receibed" >
                                    {{isset($invoice_payment) ? $invoice_payment->amount_paid : 0.00}}
                                </span>
                                {{isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'}}
                            </div>
                        </div>

                        {{-- termn, dates--}}
                        <div class="row">
                            <div class="col-3">
                                @include('back.layouts.core.forms.date-input', [
                                    'name'      => 'payment_date',
                                    'label'     => 'Payment Date' ,
                                    'class'     => 'ajax-form-field' ,
                                    'required'  => true,
                                    'attr'      => '',
                                    'value'     => date("Y-m-d"),
                                    'data'      => ''
                                ])
                            </div>
                        </div>

                        {{-- Payment method, Reference No.,  --}}
                        <div class="row">
                            <div class="col-3">
                                @include('back.layouts.core.forms.select',
                                [
                                    'name'        => 'payment_method',
                                    'label'       => 'Payment Method',
                                    'class'       => 'select2' ,
                                    'required'    => false,
                                    'attr'        => '',
                                    'data'        => [
                                        'American Express' => 'American Express',
                                        'Cash'          => 'Cash',
                                        'Cheque'        => 'Cheque',
                                        'Interac Debit' => 'Interac Debit',
                                        '45'            => 'MasterCard',
                                        'MasterCard'    => 'Paypal',
                                        'Stripe'        => 'Stripe',
                                        'Visa'          => 'Visa',
                                    ],
                                    'value'       => '',
                                ])
                            </div>

                            <div class="col-2">
                                @include('back.layouts.core.forms.text-input',
                                [
                                    'name'      => 'reference_no',
                                    'label'     => 'Reference No.',
                                    'class'     => 'ajax-form-field',
                                    'required'  => false,
                                    'attr'      => '',
                                    'data'      => '',
                                    'value'     => ''
                                ])
                            </div>
                        </div>

                        {{-- Table  --}}
                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="payment-invoices">
                                    <table id="table-payment-invoices" class="table table-striped table-bordered display">
                                        <thead>
                                        <tr>
{{--                                            <th>Select</th>--}}
                                            <th>{{__('Invoice')}}</th>
                                            <th>{{__('Due Date')}}</th>
                                            <th>{{__('Original Amount')}}</th>
                                            <th>{{__('Balance')}}</th>
                                            <th>{{__('Payment')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody id="table-payment-invoices-tbody">
                                            @if (isset($invoice) && ($invoice != null))
                                                @include('back.payment._partials.invoice-line', ['invoices' => [$invoice]])
                                            @endif

                                            @if (isset($invoice_payment) && ($invoice_payment != null))
                                                @include('back.payment._partials.invoice-row', ['invoice' => $invoice_payment->invoice])
                                            @endif

                                            @if (isset($student) && ($student != null) && !isset($invoice) && !isset($invoice_payment))
                                                @include('back.payment._partials.invoice-line', ['invoices' => $student->invoices])
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mt-3">
                                <label for="payment_message">{{__('Message on Payment')}}</label>
                                <textarea name="payment_message" class="form-control" id="payment_message" cols="50" rows="3">{{isset($invoice->properties['payment_message']) ? trim($invoice->properties['payment_message']) : ''}}</textarea>
                            </div>
                        </div>

                        {{-- save/cancel buttons--}}
                        <div class="row float-right">
                            <a onclick="app.paymentCancel()" id="payment_cancel" class="btn btn-danger" href="javascript:void(0);" role="button">{{__('Cancel')}}</a>
                            <a onclick="app.paymentSave(this)" class="btn btn-success ml-1" href="javascript:void(0);"
                               data-action="payment.storePolymorph" role="button">
                                {{__('Save')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection