<div class="col-lg-4">
    <div class="card mb-5 mb-lg-0">
        <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">{{$plan->title}}</h5>
            <h6 class="card-price text-center">${{$plan->price}}<span class="period">/{{__('month')}}</span></h6>
            <hr>
            {!! $plan->features_description !!}
            <a href="{{route('selected.plan', $plan->id)}}" class="btn btn-block btn-primary text-uppercase">{{__('Select')}}</a>
        </div>
    </div>
</div>