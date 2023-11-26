<div class="m-0 card">
    <div class="py-0 pt-2 pl-0 pr-1 ">
        <div
        id="paymentGatewayContainer"
        class="mb-0 d-flex justify-content-between btn-toggler align-items-center" data-toggle="collapse"
        data-target="#paymentGateway" aria-expanded="false" aria-controls="app_pInfo">
        <h5>{{__("Payment Gateways")}}</h5>
        <i class="mdi mdi-plus text-primary"></i>
        </div>
    </div>
    <div id="paymentGateway" class="collapse" aria-labelledby="apph_pInfo" data-parent="#paymentGatewayContainer" style="">
        <div class="p-0 card-body row">
            @foreach (ApplicationHelpers::getPaymentGateways() as $slug => $paymentGateway)
                <div class="col-md-3 mt-2">
                <div
                    class="custom-helper"
                    :class="{ 'active' : paymentGatways.includes('{{$slug}}') }"

                    @click="addPaymentGateway('{{$slug}}')"
                    >
                        <span class="isActiveIcone" x-show="paymentGatways.includes('{{$slug}}')">
                            <i class="fas fa-check-circle"></i>
                        </span>

                        <span class="custom-helper-icon">
                            <i class="{{$paymentGateway['icon']}} mr-2 text-mute"></i>
                        </span>
                        <span class="custom-helper-title">
                            {{$paymentGateway['title']}}
                        </span>
                </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
