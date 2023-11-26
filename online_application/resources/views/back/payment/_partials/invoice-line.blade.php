@foreach ($invoices as $invoice)
    @include('back.payment._partials.invoice-row')
@endforeach
