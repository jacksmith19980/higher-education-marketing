@if (!isset($properties))
@php
$properties = $field->payment->properties;
@endphp
@endif

<div class="container mt-5">
    <div class="row">
        @if (isset($properties['before_payment_text']))
        <div class="col-md-10 offset-md-1">
            {!! __($properties['before_payment_text'])  !!}
        </div>

        @endif
        @if ($invoice->status->last()->status !='Paid')
        <div class="clear"></div>
        <div class="col-md-12 mt-5">
            <div class="d-flex justify-content-center" style="margin-top: -50px;">
                @php
                $params = PaymentHelpers::getCAProperties($properties , request()->user() , $invoice);
                @endphp
                
                <form method="POST" action="{{$params['server']}}" accept-charset="utf-8">
                    
                    <input type="hidden" name="PBX_SITE" value="{{$params['pbx_site']}}">
                    <input type="hidden" name="PBX_RANG" value="{{$params['pbx_rang']}}">
                    <input type="hidden" name="PBX_IDENTIFIANT" value="{{$params['pbx_identifiant']}}">
                    <input type="hidden" name="PBX_TOTAL" value="{{$params['pbx_total']}}">
                    <input type="hidden" name="PBX_DEVISE" value="978">
                    <input type="hidden" name="PBX_CMD" value="{{$params['pbx_cmd']}}">
                    <input type="hidden" name="PBX_PORTEUR" value="{{$params['pbx_porteur']}}">
                    <input type="hidden" name="PBX_REPONDRE_A" value="{{$params['pbx_repondre_a']}}">
                    <input type="hidden" name="PBX_RETOUR" value="{{$params['pbx_retour']}}">
                    <input type="hidden" name="PBX_EFFECTUE" value="{{$params['pbx_effectue']}}">
                    <input type="hidden" name="PBX_ANNULE" value="{{$params['pbx_annule']}}">
                    <input type="hidden" name="PBX_REFUSE" value="{{$params['pbx_refuse']}}">
                    <input type="hidden" name="PBX_HASH" value="SHA512">
                    <input type="hidden" name="PBX_TIME" value="{{$params['dateTime']}}">
                    <input type="hidden" name="PBX_HMAC" value="{{$params['hmac']}}">
                    <input type="submit" class="btn btn-success" value="{{__('Pay Now')}}">
                </form>
            </div>
        </div>
        @endif
    </div>
</div>