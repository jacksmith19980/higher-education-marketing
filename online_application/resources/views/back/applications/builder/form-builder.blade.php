<div class="p-b-20">
    <h5>Sections</h5>
    <div class="list-group-item list-group-item-action flex-column align-items-start form-builder-item-sections"
        onclick="app.createSection('{{route('sections.create' , ['application' => $application->slug] )}}' , '{{__('Create Section')}}');"
    >
        <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1 text-white"><i class="fab fa-elementor p-r-5"></i>  {{__('Section')}}</h6>
        </div>
    </div>
</div>

<div class="p-b-20">
    <h5>Fields</h5>
    @foreach ($fieldsType as $type=>$item)
        @if(!in_array($item['title'], ['Signature Eversign']))
            <div class="list-group-item list-group-item-action flex-column align-items-start form-builder-item"
                data-type    = "{{$item['title']}}"
                onclick="app.createField('{{route('fields.create' , ['type'=>$type , 'application' => $application->slug , 'field_type' => $item['field_type']] )}}' , '{{__("Create New ". $item['title'] . " Field")}}');"
            >
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1 text-white"><i class="{{$item['icon']}} p-r-5"></i>  {{__($item['title'])}}</h6>
                </div>
            </div>
        @else
            @features(['quote_builder'])
                <div class="list-group-item list-group-item-action flex-column align-items-start form-builder-item"
                     data-type    = "{{$item['title']}}"
                     onclick="app.createField('{{route('fields.create' , ['type'=>$type , 'application' => $application->slug , 'field_type' => $item['field_type']] )}}' , '{{__("Create New ". $item['title'] . " Field")}}');"
                >
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 text-white"><i class="{{$item['icon']}} p-r-5"></i>  {{__($item['title'])}}</h6>
                    </div>
                </div>
            @endfeatures
        @endif
    @endforeach
</div>

<div class="p-b-20">
    <h5>Payment</h5>
    {{-- @dump($paymentGatways) --}}
    @foreach ($paymentGatways as $type=>$payment)
        @if(in_array($payment['title'], ['Stripe', 'PayPal']))
            <div class="list-group-item list-group-item-action flex-column align-items-start form-builder-item-payment" style="background-color: #0070ba" onclick="app.addPaymentGateway('{{route('payments.create' , ['gateway' => $type , 'application' => $application->slug ] )}}' , '{{__('Add Payment Gateway -'.$payment['title'])}}')" >
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1 text-white"><i class="{{$payment['icon']}} p-r-5"></i>  {{__($payment['title'])}}</h6>
                </div>
            </div>
            @else
            @features(['quote_builder'])
                <div class="list-group-item list-group-item-action flex-column align-items-start form-builder-item-payment" style="background-color: #0070ba" onclick="app.addPaymentGateway('{{route('payments.create' , ['gateway' => $type , 'application' => $application->slug ] )}}' , '{{__('Add Payment Gateway -'.$payment['title'])}}')" >
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 text-white"><i class="{{$payment['icon']}} p-r-5"></i>  {{__($payment['title'])}}</h6>
                    </div>
                </div>
            @endfeatures
        @endif
    @endforeach
</div>

<div class="p-b-20">
    <h5>Integration</h5>
    @foreach ($integrations as $type=>$integration)
        <div class="list-group-item list-group-item-action flex-column align-items-start form-builder-item-integration" style="background-color: #fb8c00"
            onclick="app.addIntegration('{{route('integration.create' , ['integration' => $type , 'application' => $application->slug ] )}}' , '{{__('Add Integration - '.$integration['title'])}}')">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1 text-white"><i class="{{$integration['icon']}} p-r-5"></i>  {{__($integration['title'])}}</h6>
            </div>
        </div>

    @endforeach

</div>
<div class="p-b-20">
    <h5>Actions</h5>
    @foreach ($actions as $type=>$action)
        @if(!in_array($action['title'], ['Eversign Signature']))
            <div class="list-group-item list-group-item-action flex-column align-items-start form-builder-item-integration" style="background-color: #f4645f"
                onclick="app.addApplicationAction('{{route('application.actions.create' , [ 'application' => $application->slug , 'action' => $type ] )}}' , '{{__('Add Action - '.$action['title'])}}')">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1 text-white"><i class="{{$action['icon']}} p-r-5"></i>  {{__($action['title'])}}</h6>
                </div>
            </div>
        @else
            @features(['quote_builder'])
                <div class="list-group-item list-group-item-action flex-column align-items-start form-builder-item-integration" style="background-color: #f4645f"
                     onclick="app.addApplicationAction('{{route('application.actions.create' , [ 'application' => $application->slug , 'action' => $type ] )}}' , '{{__('Add Action - '.$action['title'])}}')">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 text-white"><i class="{{$action['icon']}} p-r-5"></i>  {{__($action['title'])}}</h6>
                    </div>
                </div>
            @endfeatures
        @endif
    @endforeach
</div>
