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
<script src = "https://wl.flywire.com/assets/js/flywire.js" ></script>

        @if ($invoice->status->last()->status !='Paid')

            <div class="col-md-12 justify-content-center">
            <div class="d-flex justify-content-center" style="margin-top: -30px;">
                <div id = "flywire-payex" style="width:100%;min-height:700px;" 
                
                data-amount="{{$invoice->total * 100}}" 
                data-destination="{{$properties['destination']}}" 
                data-invoice-number = "{{$invoice->uid}}"
                
                @if (isset($properties['is_sandbox_account']))
                    data-sandbox = 'true'
                @endif    

                @if($parent && $user)
                    
                    data-sender-first-name = "{{$parent->first_name}}"
                    data-sender-last-name = "{{$parent->last_name}}"
                    data-sender-email = "{{$parent->email}}"

                    data-student-id = "{{$user->reference}}"
                    data-student-first-name = "{{$user->first_name}}"
                    data-student-last-name = "{{$user->last_name}}"
                    
                    

                @else    

                    data-sender-first-name = "{{$user->first_name}}"
                    data-sender-last-name = "{{$user->last_name}}"
                    data-sender-email = "{{$user->email}}"

                    data-student-id = "{{$user->reference}}"
                    
                    data-student-first-name = "{{$user->first_name}}"
                    data-student-last-name = "{{$user->last_name}}"
                   
                    

                @endif
                data-call-back="
                            {{ route('payment.response' , [ 
                                    'school' => $school , 
                                    'student' => $user,
                                    'gateway' => 'flywire'
                                ] )
                            }}"></div>

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