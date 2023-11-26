@extends('front.layouts.parents')

@section('content')

<div class="page-wrapper" style="padding-top: 100px;">

    <div class="container-fluid">

        <div class="row">

            @if (isset($bookings) && $bookings->count())

            <div class="col-lg-7">
                @include('front.parent._partials.booking.parent-bookings')
            </div>

            <div class="col-lg-5">
                @include('front.parent._partials.child.children')
            </div>

            @else

            <div class="col-lg-8 offset-lg-2">
                @include('front.parent._partials.applications.index')
            </div>

            @endif

        </div>

    </div>


</div>


<div id="confirm-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-body">
                <strong>{{__('Are you sure you want to delete this?')}}</strong>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok text-white">{{__('Delete')}}</a>
            </div>

        </div>

        <!-- /.modal-content -->

    </div>

    <!-- /.modal-dialog -->

</div>

@endsection
