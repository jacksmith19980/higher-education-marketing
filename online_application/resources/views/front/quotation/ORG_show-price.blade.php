<div class="col-md-6 offset-md-3">
    
    <div class="alert alert-default row">

        <div class="col-md-4">
            <span>{{__('Course')}}:</span>
            <h4>{{$course->title}}</h4>
        </div>
        
        <div class="col-md-4">
            <span>{{__('Start Date')}}:</span>
            <h4>{{$startDate}}</h4>
        </div>
        
        <div class="col-md-4">
            <span>{{__('End Date')}}:</span>
            <h4>{{$endDate}}</h4>
        </div>
        <hr>
    </div>
    
    <div class="alert alert-default row">
        <div class="col-md-6">
            <span>{{__('Course Price')}}</span>
            <h4>{{$numberofWeeks}} {{__('Weeks')}} - <span class="text-info price">{{number_format($coursePrice)}} {{$settings['school']['default_currency']}}</span></h4>
        </div>
        
        @if(isset($accommodation))
            <div class="col-md-6">
                <span>{{__('Accommodation')}}</span>
                <h4>{{$accommodation}} ({{$numberofWeeks}} {{__('Weeks')}}) - <span class="text-info price">{{number_format($accommodationPrice)}} {{$settings['school']['default_currency']}}</span></h4>
            </div>
        @endif
        
        @if(isset($transfer))
            <div class="col-md-6">
                <span>{{__('Transfer')}}</span>
                <h4>{{$transfer}} - <span class="text-info price">{{number_format($transferPrice)}} {{$settings['school']['default_currency']}}</span></h4>
            </div>
        @endif
        
        <div class="col-md-6">
            <span>{{__('Other Charges')}}</span>
            <h4>{{__('Registeration Fees')}} - <span class="text-info price">{{number_format($registerationFees)}} {{$settings['school']['default_currency']}}</span></h4>
            <h4>{{__('Material Fees')}} - <span class="text-info price">{{number_format($materialFees)}} {{$settings['school']['default_currency']}}</span></h4>
        </div>
    </div>
    <div class="alert bg-default row">
        <div class="col-md-12 clearfix">
            <h3 class="float-right text-success m-b-0 p-b-0"><small>{{__('YOUR PRICE:')}}</small> {{ number_format($totalPrice)}} {{$settings['school']['default_currency']}}</h3>
        </div>
    </div>
    
    <div class="alert row p-0">
        <div class="col-md-12" id="SendViaEmailFrom"></div>
        <div class="col-md-6">

            <a href="javascript:void(0)" onclick="app.sendQuotationViaEmail(this)" class="btn btn-lg btn-info float-left sendViaEmailButton" style="width:100%">{{__('SEND VIA EMAIL')}}</a>
        </div>
        
        <div class="col-md-6">
            <a href="{{route('school.register' , $school)}}" class="btn btn-lg btn-success float-right" style="width:100%">{{__('BOOK NOW')}}</a>
        </div>

    </div>




    
    
</div>