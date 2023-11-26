<div class="card bg-light border-bottom border-info">

    <div class="card-header bg-light">

        <div class="d-flex align-items-start">

            <span class="text-info display-10"><i class="ti-user"></i></span>

            <h4 class="m-l-10 text-info">{{$child->name}}</h4>

        </div>

    </div>

    <div class="card-body">

        <h5 class="card-title">{{__('Bookings')}}</h5>

        @include('front.parent._partials.child.child-submissions')

    </div>

</div>