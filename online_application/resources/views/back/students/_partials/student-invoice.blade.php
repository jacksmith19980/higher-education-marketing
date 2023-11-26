<div class="card">
    <div class="card-header">
        <h5 class="float-left">
            <i class="fa fa-circle {{($invoice->enabled) ? 'text-success' : 'text-danger'}}" style="font-size:70%"
               data-toggle="tooltip" data-placement="top" title=""
               data-original-title="{{($invoice->enabled) ? 'Enables' : 'Disabled'}}"></i>


            {{__('Invoice#')}}: {{$invoice->uid}}

            <a href="{{ route('invoice.pdf.action' , ['invoice' => $invoice , 'action' => 'view'] )}}" target="_blank"
               class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"
               data-original-title="View Invoice">
                <i class="icon-eye"></i>
            </a>

            <a href="{{ route('invoice.pdf.action' , ['invoice' => $invoice , 'action' => 'download'] )}}"
               target="_blank"
               class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"
               data-original-title="Download Invoice">
                <i class="icon-arrow-down-circle"></i>
            </a>

            <a href="javascript:void(0)"
               onclick="app.changeInvoiceAsPaid(`{{route('invoices.createPaid', $invoice)}}`)"
               class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"
               data-original-title="Change as paid">
                <i class="icon-shuffle"></i>
            </a>

            <a href="javascript:void(0)"
               onclick="app.deleteInvoice(`{{route('invoices.destroy', $invoice)}}`)"
               class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"
               data-original-title="Delete Invoice">
                <i class="icon-trash text-danger"></i>
            </a>

            <small class="d-block text-muted">{{__('Payment Gateway')}}
                : {{ ucwords($invoice->payment_gateway) }}</small>
        </h5>

        <h5 class="float-right">
            {{__('Amount')}}
            : {{$invoice->total}}{{(isset($settings['school']['default_currency']))? $settings['school']['default_currency'] : 'USD'  }}

            <small class="d-block text-muted">{{__('Created at')}}: {{ $invoice->created_at->diffForHumans() }}</small>
        </h5>
    </div>

    <div class="card-body">
        @if (isset($invoice->status))
            <div class="list-group">
                @foreach ($invoice->status as $status)
                    @include('back.students._partials.student-invoice-status')
                @endforeach
            </div>
        @endif
    </div>
</div>
