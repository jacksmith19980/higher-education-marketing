@extends('back.layouts.core.helpers.table' ,
[
'show_buttons' => false,
'title' => $promocode->code,
]
)
@section('table-content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-lg-3 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30">
                        <h4 class="card-title m-t-10">{{$promocode->code}}</h4>
                            <i class="fa fa-circle {{ $promocode->isActive() ? 'text-success' : 'text-danger' }}"
                               data-toggle="tooltip" data-placement="top"
                               title={{ $promocode->isActive() ? __('Active') : __('Inactive') }}></i>
                        {{$promocode->expires_at->diffForHumans()}}

                    </center>

                </div>
                <div>
                    <hr>
                </div>
            </div>
        </div>


        <div class="col-lg-9 col-xlg-9 col-md-7">
            <div class="card">

                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-promocode-tab" data-toggle="pill" href="#promocode"
                            role="tab" aria-controls="pills-promocode" aria-selected="true">
                            {{__('Promocode')}}
                        </a>
                    </li>

{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" id="pills-dates-tab" data-toggle="pill" href="#promocodeable" role="tab"--}}
{{--                            aria-controls="pills-dates" aria-selected="false">--}}
{{--                            {{__('Promocodeable')}}--}}
{{--                        </a>--}}
{{--                    </li>--}}

                    <li class="nav-item">
                        <a class="nav-link" id="pills-promocodeusable-tab" data-toggle="pill" href="#promocodeusable" role="tab"
                            aria-controls="pills-promocodeusable" aria-selected="false">
                            {{__('Promocodeusable')}}
                        </a>
                    </li>

                </ul>

                <div class="tab-content" id="pills-tabContent">

                    @include('back.promocodes._partials.promocode-tab')

{{--                    @include('back.promocodes._partials.promocodeable-tab')--}}

                    @include('back.promocodes._partials.promocodeusable-tab')

                </div>
            </div>

        </div>
    </div>
</div>
@endsection