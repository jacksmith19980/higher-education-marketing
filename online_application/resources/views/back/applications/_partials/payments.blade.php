<div class="col-sm-12 col-lg-4" data-payment-id="{{$paymentGateWay->id}}">
    <div class="card bg-info">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="m-r-10">
                    <h1 class="m-b-0"><i class="fas fa-bolt text-white"></i></h1></div>
                <div>
                    <h2 class="font-20 text-white m-b-5 op-7 integration-title">{{$paymentGateWay->name}}</h2>
                    <h6 class="text-white font-medium m-b-0">{{ strtoupper($paymentGateWay->slug) }}</h6>
                </div>
                <div class="ml-auto">
                    <div class="crypto">
                        <canvas style="display: inline-block; width: 58px; height: 30px; vertical-align: top;"
                                width="58" height="30"></canvas>
                    </div>
                </div>
            </div>
            <div class="row text-center text-white m-t-30">

                <div class="col-6">
                    <a href="javascript:void(0)" class="action-button" data-toggle="tooltip" data-placement="top"
                       title="{{__('Edit')}}"
                       onclick="app.editPaymentGateWay( '{{ route('payments.edit' , ['payment' => $paymentGateWay , 'application' => $application ] ) }}' , {{ json_encode( [] ) }} , '{{__( "Edit Payment - ".$paymentGateWay->name ) }}' , this )">

                        <i class="ti-pencil-alt text-white app-action-icons"></i>
                    </a>
                </div>
                <div class="col-6">
                    <a href="javascript:void(0)" class="action-button"
                       onclick="app.deleteElement('{{route('payments.destroy' , $paymentGateWay)}}' , {{ json_encode( [ 'id' => $paymentGateWay->id ] )  }} , 'data-payment-id')"
                       data-toggle="tooltip" data-placement="top" title="{{__('Delete')}}">
                        <i class="ti-trash text-white app-action-icons"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
