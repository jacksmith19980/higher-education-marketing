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

        @if ($invoice->status->last()->status !='Paid')

            <div class="col-md-12 justify-content-center">
            <div class="d-flex justify-content-center" style="margin-top: -30px;">
                MOERIS
                <iframe id="monerisFrame"
                    src="https://esqa.moneris.com/HPPtoken/index.php?id=htFK3BRMIJI7I47&pmmsg=true&css_body;&css_textbox=border-width:2px;&css_textbox_pan=width:140px;&enable_exp=1&css_textbox_exp=width:40px;&enable_cvd=1&css_textbox_cvd=width:40px"
                    frameborder='0' width="600px" height="300px"></iframe>

                    <input type="button" onClick="doMonerisSubmit()" value="submit iframe">

            </div>
            </div>
        @else
        <div class="container mt-5">
	<div class="row">
                <strong>Thank you for your payment, this invoice has been already paid</strong>
            </div>
            </div>
        @endif

        </div>

    </div>

</div>

@endsection
