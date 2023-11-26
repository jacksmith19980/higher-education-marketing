@extends('front.layouts.payment')
@section('content')
<div class="page-wrapper" style="padding-top:30px;display:block">
    <div class="container-fluid">
        <div class="row">
            @if (!isset($properties))
            @php
            $properties = $paymentGateWay->properties;
            @endphp
            @endif
            
            
            <div class="col-md-6 offset-md-3 mt-5">
                <div class="card">
                    <div class="card-body">
                        
                        @if ($invoice->status->last()->status !='Paid')
                        
                        @isset($properties['before_payment_text'])
                        <div class="clearfix m-b-20">
                            {!! __($properties['before_payment_text'])  !!}
                        </div>
                        @endisset

                        <div class="d-flex justify-content-center">
                        @php
                            $params = PaymentHelpers::getCAProperties($properties , request()->user() , $invoice);
                        @endphp
                        
                        <form method="POST" action="{{$params['server']}}">
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

                        @else
                        
                            <div class="col-md-6 offset-md-3">
                            
                                <div class="alert alert-success text-center">
                            
                                    <p>{{__('Thank you for your payment')}}</p>
                            
                                </div>
                            
                            </div>
                        
                        @endif
                        
                        
                    </div>
                </div>


            </div>

           

            
        </div>
    </div>
</div>
@endsection