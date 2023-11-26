<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-md-flex align-items-center">
                <div>
                    <h4 class="card-title">{{__('Children')}}</h4>
                </div>
                <div class="ml-auto d-flex no-block align-items-center">
                    <div class="dl m-b-15">
                    </div>
                </div>
            </div>
            @if ($children->count())
            
                @foreach ($children as $child)
                    
                    @include('front.parent._partials.child.child' , ['child'=> $child] )

                @endforeach

                @else
                
                <div class="alert alert-warning">
                    <span>{{ __('You didn\'t add any child yet' )}}</span>

                </div>

            @endif

        </div>
    </div>
</div>