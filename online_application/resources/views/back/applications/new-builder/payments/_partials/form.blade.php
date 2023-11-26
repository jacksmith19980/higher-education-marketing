@if ($method == 'PUT')
    <h4 class="mb-3">{{__('Edit Payment Gateway')}} ({{__($title)}})</h4>
@else
    <h4 class="mb-3">{{__('Add Payment Gateway')}} ({{__($title)}})</h4>
@endif
<form  @submit.prevent="storePaymentGateway('{{$route}}' , '{{$method}}')"   class="text_input_field" id="fieldEdit" enctype="multipart/form-data">

    @csrf
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#general" role="tab"><span class="hidden-xs-down">{{__('General')}}</span></a> </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#products" role="tab">
                <span class="hidden-xs-down">{{__('Products')}}</span>
            </a>
        </li>

    </ul>

<!-- Tab panes -->
    <div class="tab-content tabcontent-border">
        <div class="tab-pane p-20 active" id="general" role="tabpanel">
            @include('back.applications.new-builder.payments.'.$gateway.'.general')
        </div>
        <div class="tab-pane p-20" id="products" role="tabpanel">
            @include('back.applications.new-builder.payments._partials.products')
        </div>
    </div>

    <div id="sideMenuFooter">
        <div style="text-align:left">
            @if ($payment )
                <x-builder.delete
                action="{{route('payments.destroy', [
                    'payment'        => $payment
                ] )}}"
                item="payment-gateway"
                buttonText="{{__('Delete')}}"></x-builder.delete>

            @endif
        </div>
        <div style="text-align:rigth; width:50%;">
            <button class="btn btn-light" @click="closeEditField">{{__('Cancle')}}</button>

            <button type="submit" class="btn btn-success">{{ ($method == 'PUT') ? __('Edit') :  __('Save')}}</button>
        </div>
    </div>


    {{--  <div id="sideMenuFooter">
        <button class="btn btn-light" @click="closeEditField">{{__('Cancle')}}</button>
        <button type="submit" class="btn btn-success">{{ ($method == 'PUT') ? __('Edit') :  __('Save')}}</button>
    </div>  --}}



</form>
