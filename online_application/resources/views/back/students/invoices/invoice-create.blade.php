@extends('back.layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span id="invoice_text">
                            @if (isset($invoice))
                                    {{__('Invoice')}} </span><span id="invoice_text_id">{{$invoice->uid}}</span>
                            @else
                                    {{__('New invoice')}}  </span><span id="invoice_text_id"></span>
                            @endif
                        </h4>
                        <hr>
                        @include('back.layouts.core.forms.hidden-input',
                                [
                                    'name'          => 'invoice_id',
                                    'label'         => '',
                                    'class'         => '' ,
                                    'required'      => false,
                                    'attr'          => '',
                                    'data'          => '',
                                    'value'         => isset($invoice) ? $invoice->uid : '',
                                ])

                        {{-- customer, email, balance--}}
                        <div class="row">
                            <div class="col">
                                @include('back.layouts.core.forms.select',
                                [
                                    'name'          => 'customer',
                                    'label'         => 'Student',
                                    'class'         => 'select2',
                                    'required'      => true,
                                    'attr'          => 'onchange=app.getCustomerInfo("'.route('students.showJson').'")',
                                    'data'          => $students,
                                    'value'         => isset($student) ? $student->id : '',
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
                                    'value'     => isset($student) ? $student->email : '',
                                ])
                            </div>

                            <div class="col-2">
                                <span class="total-blue">{{__('Balance Due')}}:</span> <br>
                                <span id="due-balance" >
                                    {{isset($invoice) ? $invoice->total : '0.00'}} {{isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'}}
                                </span>
                            </div>

                        </div>

                        {{-- address--}}
                        <div class="row">
                            <div class="col-10">
                                <div class="form-group">
                                    <label for="billing_address">Billing Address</label>
                                    @if (isset($invoice))
                                        <textarea name="billing_address" class="form-control" id="billing_address" rows="2">{{$invoice->properties['billing_address'] ?? null }}</textarea>
                                    @elseif(isset($student))
                                        <textarea name="billing_address" class="form-control" id="billing_address" rows="2">{{$student->fullAddress}}</textarea>
                                    @else
                                        <textarea name="billing_address" class="form-control" id="billing_address" rows="2"></textarea>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- termn, dates--}}
                        <div class="row">
                            <div class="col-3">
                                @include('back.layouts.core.forms.select',
                                [
                                    'name'        => 'terms',
                                    'label'       => 'Terms',
                                    'class'       => 'select2' ,
                                    'required'    => false,
                                    'attr'        => 'onchange=app.changeDueDate(this)',
                                    'data'        => [
                                        '0' => 'Due on receipt',
                                        '10' => 'Next 10',
                                        '15' => 'Next 15',
                                        '30' => 'Next 30',
                                        '45' => 'Next 45',
                                        '60' => 'Next 60'],
                                    'value'       => '',
                                ])
                            </div>
                            <div class="col-3">
                                @include('back.layouts.core.forms.date-input', [
                                    'name'      => 'invoice_date',
                                    'label'     => 'Invoice Date' ,
                                    'class'     => 'ajax-form-field',
                                    'required'  => true,
                                    'attr'      => '',
                                    'value'     => isset($invoice) ? $invoice->created_at->format("Y-m-d") : date("Y-m-d"),
                                    'data'      => ''
                                ])
                            </div>
                            <div class="col-3">
                                @include('back.layouts.core.forms.date-input', [
                                    'name'      => 'due_date',
                                    'label'     => 'Due Date' ,
                                    'class'     => 'ajax-form-field',
                                    'required'  => true,
                                    'attr'      => '',
                                    'value'     => isset($invoice) ? $invoice->due_date : date("Y-m-d"),
                                    'data'      => ''
                                ])
                            </div>
                        </div>

                        {{-- table--}}
                        <div class="invoice-products mt-5">
                            <table id="table-invoice-products" class="table table-striped table-bordered display">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Taxable</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="table-invoice-products-tbody">
                                    @if (isset($invoice))
                                        @foreach ($invoice->invoiceables()->get() as $product)
                                            @include('back.students.invoices.product-new-line', [
                                                'order' => rand(1000, 10000),
                                                'product' => $product,
                                                'products' => $products
                                            ])
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        {{-- add product and total--}}
                        <div class="row">
                            <div class="col-10">
                                <button data-name="settings_users_save_button" class="btn btn-info"
{{--                                        onclick="app.addProduct('{{route('invoices.createProduct')}}')"--}}
                                        onclick="app.addProductLine(this)" data-action="invoice.createProduct"
                                        data-container="table-invoice-products-tbody" @if(isset($count)) data-count="{{$count}}" @endif
                                >{{__('Add Product')}}</button>


                                <button data-name="settings_users_save_button" class="btn btn-info ml-1"
                                        onclick="app.addDeleteProducts()">{{__('Delete All')}}</button>
                            </div>
                            <div class="pull-right">
                                {{__('Total')}}:
                                <span id="total-table" class="invoice-total">
                                    {{isset($invoice) ? $invoice->total : '0.00'}}
                                </span>
                                {{isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'}}
                            </div>
                        </div>

                       {{-- invoice message--}}
                        <div class="row">
                            <div class="form-group mt-3">
                                <label for="invoice_message">Message on invoice</label>
                                <textarea name="invoice_message" class="form-control" id="invoice_message" cols="50" rows="3">{{isset($invoice->properties['invoice_message']) ? trim($invoice->properties['invoice_message']) : ''}}</textarea>
                            </div>
                        </div>

                       {{-- save/cancel buttons--}}
                        <div class="row float-right">
                            <a onclick="app.invoiceCancel()" id="invoice_cancel" class="btn btn-danger" href="javascript:void(0);" role="button">Cancel</a>
{{--                            <a onclick="app.invoiceSave()" class="btn btn-success ml-1" href="javascript:void(0);" role="button">Save</a>--}}

                            <div class="btn-group ml-1 dropdown">
                                <button id="invoice_save" onclick="app.invoiceSave(this, 'save')" type="button"
                                        class="btn btn-success" data-action="invoice.storePolymorph">
                                    @if (isset($invoice)) Update @else Save @endif
                                </button>
                                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                    <a id="invoice_close" onclick="app.invoiceSave(this, 'close')" class="dropdown-item"
                                       href="javascript:void(0);" data-action="invoice.storePolymorph">
                                        @if (isset($invoice)) Update @else Save @endif and close
                                    </a>
                                    <a id="invoice_new" onclick="app.invoiceSave(this, 'new')" class="dropdown-item"
                                       href="javascript:void(0);" data-action="invoice.storePolymorph">
                                        @if (isset($invoice)) Update @else Save @endif and create other
                                    </a>
{{--                                    <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                    <div class="dropdown-divider"></div>--}}
{{--                                    <a class="dropdown-item" href="#">Separated link</a>--}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
