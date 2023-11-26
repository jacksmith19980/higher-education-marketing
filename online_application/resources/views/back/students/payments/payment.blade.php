@extends('back.layouts.invoice-pdf')
@section('page-title')
   | {{__('Payment')}}
@endsection

@section('content')
<section class="invoice-section">
    <div class="invoice-wrapper">
        <div class="invoice-header">
            <div class="left-pane">
                <p class="ih-school-info">
                    {!!  isset($settings['school']['school_details']) ? $settings['school']['school_details'] : ''!!}
                </p>

                <p class="ih-invoice-sum">
                    {{isset($invoice->properties['billing_address']) ? $invoice->properties['billing_address'] : ''}}
                    </p>
                </div>
                <div class="b-divider"></div>
                <div class="right-pane">
                    {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}

                    <div class="receipt-data">
                        <table>
                            <tbody>
                            <tr>
                                <td>
                                    <h3 class="rc-title ">{{__('Receipt')}}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>{{$payment->created_at->format('Y-m-d')}}</h5>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="invoice-content">
                <table class="invoice-table">
                    <thead>
                    <tr>
                        <th>{{__('Invoice')}}</th>
                        <th>{{__('Payment')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="top-rd-whitespace">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="top-rd">
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="fluid-td">#{{$invoice->uid}}</td>
                            <td>{{ $payment->amount_paid }} {{$currency}}</td>
                        </tr>

                        <tr class="bottom-rd">
                            <td colspan="2"></td>
                        </tr>
                    </tbody>

                </table>
                <table class="table-footer">
                    <tbody>
                    <tr>
                        <td class="has-content two-span" colspan="2">{{__('Amount Paid')}}</td>
                        <td class="has-content">{{ $payment->amount_paid }} {{$currency}}</td>
                    </tr>
                    </tbody>

                </table>
            </div>

            @if (isset($payment->properties['invoice_message']))
                <div class="invoice-comment">
                    <div class="invoice-comment-container">
                        {{$payment->properties['invoice_message']}}
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
