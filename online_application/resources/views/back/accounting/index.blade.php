{{--@extends('back.layouts.default')--}}
@extends('back.layouts.core.helpers.table' , ['show_buttons' => false,'title'=> 'Invoices and Payments'])

@section('table-content')

<div class="row pb-2" id="datatableNewFilter">
    <div class="col-md-4 col-xs-12 d-flex" id="lenContainer">
    </div>
    <div class="dropdown ml-auto">
        <a class="btn btn-default dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            New
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('invoices.createPolymorph')}}">Invoice</a>
            <a class="dropdown-item" href="{{route('payment.createPolymorph')}}">Receive Payment</a>
        </div>
    </div>
</div>

        <table id="accounting_table" data-route="{{route('accounting.getInvoicePayment')}}" data-i18n="{{$datatablei18n}}"
               class="table new-table table-bordered nowrap display">

            <thead>

            <tr>
                <th>{{__('Date')}}</th>
                <th>{{__('Transaction Type')}}</th>
                <th>{{__('Payment Method')}}</th>
                <th>{{__('No.')}}</th>
                <th>{{__('Due Date')}}</th>
                <th>{{__('Student')}}</th>
                <th>{{__('Balance')}}</th>
                <th>{{__('Status')}}</th>
                <th>{{__('Total')}}</th>
                <th class="control-column"></th>
            </tr>
            </thead>
        </table>

@endsection