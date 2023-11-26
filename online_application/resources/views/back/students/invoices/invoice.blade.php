@extends('back.layouts.invoice-pdf')
@section('page-title')
   {{__('Invoice')}}
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
                        <strong>{{$invoice->student->name}}</strong>
                        <p>{{isset($invoice->properties['billing_address']) ? $invoice->properties['billing_address'] : ''}}</p>
                    </p>
                </div>
                {{--  <div class="b-divider" style="height: 100%; marging:20px 0;"></div>  --}}
                <div class="right-pane">
                    {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}

                    <div class="receipt-data">
                        <table>
                            <tbody>
                            <tr>
                                <td style="width:40%;"><h3 class="rc-title">{{__('Invoice')}}</h3></td>
                                <td style="width:60%;">
                                    <h4>#{{$invoice->uid}}</h4>
                                    {{__('Date')}} : {{$invoice->created_at->format('Y-m-d')}}<br>
                                    {{__('Deadline')}} : {{isset($invoice->due_date) ? $invoice->due_date : ''}}
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
                        <th>{{__('QTE')}}</th>
                        <th class="fluid-td">{{__('DESCRIPTION')}}</th>
                        <th>{{__('AMOUNT')}}</th>
                        <th>{{__('TOTAL')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr class="top-rd-whitespace">
                            <td colspan="4"></td>
                        </tr>
                        <tr class="top-rd">
                            <td colspan="4"></td>
                        </tr>
                        <tr class="topb-rd-whitespace">
                            <td colspan="4"></td>
                        </tr>
                        @foreach ($invoice->invoiceables()->get() as $product)

                            <tr>
                                <td>{{ $product->quantity }} </td>
                                @if (isset($product->properties['description']) && $product->properties['description'] != '')
                                    <td>{{$product->properties['description']}}</td>
                                @else
                                    <td>{{$product->title}}</td>
                                @endif

                                <td><span>{{ $product->amount }} {{$currency}}</span></td>

                                <td>{{ $product->amount }} {{$currency}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3"></td>
                            <td class="total-td">{{$invoice->total}} {{$currency}}</td>
                        </tr>

                        <tr class="bottom-rd">
                            <td colspan="4"></td>
                        </tr>
                        <tr class="botb-rd-whitespace">
                            <td colspan="4"></td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <td></td>
                        <td></td>
                        <td class="has-content">{{__('TOTAL')}}</td>
                        <td class="has-content">{{$invoice->total}} {{$currency}}</td>
                    </tfoot>
                </table>
            </div>
            <div class="invoice-comment">
                <div class="invoice-comment-container">
                    {{isset($invoice->properties['invoice_message']) ? $invoice->properties['invoice_message'] : ''}}
                </div>
            </div>
        </div>
    </section>
@endsection
