@component('mail::quotation-message')

# {{$school->name}} Quotation

Dears {{$data['first_name']}},

<table style="width:100%;">

    <tr>
        <td>
            <p>Please find below the quote for attending:</p>
        </td>
    </tr>
    <tr>
        <td>
            @foreach ($invoice->invoice['courses_addons']['courses'] as $course)
            <h4 class="d-block text-info">{{$course['course']}} </h4>
            @foreach ($course['selectedDates'] as $dates)
            <small class="d-block text-muted"
                style="display:block">{{ QuotationHelpers::formateStartEndDates($dates) }}</small>
            @endforeach
            @endforeach
        </td>
    </tr>
    <tr>
        <td>

            <h4>Your price is:
                <strong>{{$settings['school']['default_currency']}}{{ number_format($invoice->invoice['totalPrice'])}}</strong>
            </h4>

            <p>If you would like to proceed with booking this place, please click on the button below.</p>
            {{--  <h2 class="float-right text-success m-b-0 p-b-0"><small>{{__('YOUR PRICE:')}}</small>
            {{ number_format($invoice->invoice['totalPrice'])}} {{$settings['school']['default_currency']}}</h2> --}}
            {{--  @include('front.quotation.email.invoice-details' , ['booking' => $invoice])  --}}
        </td>
    </tr>
    <tr>
        <td style="text-align:center">
            @component('mail::button', ['url' => route('school.register' , [
            'school' => $school,
            'booking' => $invoice->id,
            'user' => $invoice->user_id
            ])
            ])
            Book Now
            @endcomponent
        </td>
    </tr>
    <tr>
        <td>
            <p>Please do not hesitate to contact us if you require any further information.</p>
        </td>
    </tr>
</table>
{!! $settings['school']['email_signature'] !!}
@endcomponent