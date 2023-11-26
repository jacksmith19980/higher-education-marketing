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
            @foreach ($invoice->invoice['details']['courses'] as $course)
                <h4 class="d-block text-info">{{$course['title']}} </h4>
                @foreach ($course['dates'] as $date)
                    <strong class="d-block text-muted" style="display:block">
                            {{ QuotationHelpers::formateStartEndDates($date['start'].':'.$date['end']) }}
                            <span>(@price({{number_format($date['price'])}}))</span>
                    </strong>
                    
                    @if (isset($date['addons']))
                        <ul style="list-style: none">
                            <strong>{{__('Add-ons')}}</strong>
                            @foreach ($date['addons'] as $addon)
                                <li>
                                    {{$addon['title']}}
                                    <span>(@price({{number_format($addon['price'])}}))</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            @endforeach
        </td>                
    </tr>
    @if (isset($invoice->invoice['details']['transfer']))
    <tr>
        <td>    
            <strong>{{__('Transfer')}}</strong>
        </td>
    </tr>
    @foreach ($invoice->invoice['details']['transfer'] as $transfer)
        <tr>
            <td>
                {{$transfer['option']}} (@price({{number_format($transfer['price'])}}))
            </td>
        </tr>
    @endforeach

    @endif
    
    @if (isset($invoice->invoice['details']['accommodation']))
    <tr>
        <td>    
            <strong>{{__('Accommodation')}}</strong>
        </td>
    </tr>
    @foreach ($invoice->invoice['details']['accommodation'] as $transfer)
        <tr>
            <td>
                {{$transfer['option']}} (@price({{number_format($transfer['price'])}}))
            </td>
        </tr>
    @endforeach

    @endif
    
    <tr>
        <td>    
            <h4>Your price is: <strong>@price({{number_format($invoice->invoice['totalPrice'])}})</strong></h4>
            <p>If you would like to proceed with booking this place, please click on the button below.</p>
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