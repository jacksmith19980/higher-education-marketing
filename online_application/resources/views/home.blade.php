@extends('back.layouts.minimal')

@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
    style="background:url({{asset('assets/images/big/auth-bg.jpg')}}) no-repeat center top;">
    <div class="home-box">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-info">
                        <div class="card-header bg-info">
                            <h4 class="text-white m-b-0">{{__('Schools')}}</h4>
                        </div>
                        <div class="card-body">

                            <div class="card-text add-school">
                                @if( auth()->user()->schools->count())
                                {{-- @include('back.layouts.core.helpers.button-add', ['route' => "schools.create",
                                'text' => "Add School"])--}}
                            </div>
                            <table class="table table-striped table-bordered display">
                                <tbody>
                                    @foreach ( auth()->user()->schools as $school)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tenant.switch' , $school) }}">{{$school->name}}</a>
                                        </td>
                                        @if(auth()->guard('web')->user()->isHem)
                                            <td>
                                                {{$school->plan->title}}
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="alert alert-warning" style="text-align: left">
                                <h3 class="text-warning"><i class="fa fa-exclamation-circle"></i> Start Now</h3> Get
                                started by adding your first school. Then build you first application!

                                <p style="padding: 15px 40px 0 40px;color:#fff">
                                    <a href="{{route('schools.create')}}"
                                        class="btn waves-effect waves-light btn-block btn-info">Add School</a>
                                </p>
                            </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
