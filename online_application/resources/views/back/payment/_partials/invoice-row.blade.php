<tr>

    @include('back.layouts.core.forms.hidden-input', [
        'name'          => 'invoice_id',
        'label'         => '',
        'class'         => '' ,
        'required'      => false,
        'attr'          => '',
        'data'          => '',
        'value'         => $invoice->id,
    ])

    {{--        <td>--}}
    {{--            @include('back.layouts.core.forms.checkbox', [--}}
    {{--                'name'          => 'active_'.$invoice->id,--}}
    {{--                'label'         => '',--}}
    {{--                'class'         => 'ajax-form-field',--}}
    {{--                'required'      => true,--}}
    {{--                'attr'          => '',--}}
    {{--                'default'       => 'true',--}}
    {{--                'helper_text'   => '',--}}
    {{--                'value'         => ''--}}
    {{--            ])--}}
    {{--        </td>--}}

    <td>
        {{__('Invoice')}} {{$invoice->id}} ({{\Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d')}})
    </td>

    {{--    <td class="description-{{$order}}"></td>--}}

    <td>
        {{\Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d')}}
    </td>

    <td>
        {{$invoice->total}}
        {{isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'}}
    </td>

    <td class="open_balance">
        @php
            $total_paid = 0;

        foreach ($invoice->payments as $payment){
            $total_paid = $total_paid + $payment->amount_paid;
        }
        @endphp

        {{number_format($invoice->total - $total_paid, 2, '.', '')}}
        {{isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'}}
    </td>

    <td>
        @include('back.layouts.core.forms.text-input', [
        'name'      => 'payment',
        'label'     => '',
        'class'     => 'ajax-form-field',
        'required'  => false,
        'attr'      => 'onkeyup=app.changeInvoiceAmount(this)',
        'data'      => '',
        'value'     => isset($invoice_payment) ? $invoice_payment->amount_paid : ''
    ])
    </td>
</tr>