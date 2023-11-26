@extends('front.layouts.quotation-layout')

@section('content')

<div class="page-wrapper" style="padding-top: 100px; display: block;">

<div class="container-fluid">

<div class="row">
<div class="col-md-6 offset-md-3">
<div class="card border-bottom border-success">

<div class="card-header bg-info">
<h4 class="m-b-0 text-white">{{__( $quotation->title )}}</h4>
</div>

<div class="card-body">

<h4 class="m-b-20">{{__('First Step : Build your quotation')}}</h4>
@if (isset($quotation->description))

    <div class="alert alert-default">{!!$quotation->description!!}</div>
    
@endif

<form action="#" method="POST" class="priceForm">
    @csrf

        <input type="hidden" name="quotation" value="{{ $quotation->id }}" />
        <input type="hidden" name="user_id"   value="{{ $userId }}" />
           
            @include('front.quotation._partials.campus.index')
            
            @include('front.quotation._partials.accommodation.index')
            
            @include('front.quotation._partials.transfer.index')
            
            @include('front.quotation._partials.miscellaneous.index')

        <div class="card-footer">
            <a href="javascript:void(0)" class="btn btn-lg btn-success float-right GetAPriceButton hidden quotation-extras" onclick="app.getPrice(document.querySelector('form'))">{{__('GET A PRICE')}}</a>
            <div class="clearfix"></div>
        </div>
        
</form>
</div>

</div>

</div>				


</div>
<div class="row" id="CoursePrice"></div>

</div>




</div>
@endsection

