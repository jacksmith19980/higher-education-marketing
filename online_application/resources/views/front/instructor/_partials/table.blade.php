@extends('front.layouts.instructors')
@section('content')
    
    <div class="row justify-content-center">
        
            @include('back.layouts.core.helpers.page-actions')
        
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        @yield('table-content')
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
