@extends('front.layouts.agents')
@section('content')

    <div class="page-wrapper" style="padding-top: 100px;">

        <div class="container-fluid">
            <div class="sec-app-dashboard">
                <div class="agent-dashboard-container px-2 py-4 p-md-4 mb-4 box-shadow ">
                    <div class="agent-dashboard-header">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="d-flex align-items-center">
                                    <div class="col flex-grow-1">
                                        <div class="applicant-main-info">
                                            <p class="mb-1">{{__('Welcome back!')}}</p>
                                            <h4 class="mb-1 text-primary">{{ $agent->name }}</h4>
                                            <small class="d-block">{{__('Agent')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12 my-4 mb-md-2">
                        {{--  <h3 class="app-col-title text-primary my-4 mb-md-2">{{__('Most Recent Students')}}</h3>  --}}
                        <div id="lang_opt_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <!-- <div class="container-fluid">
						<div class="row">

							<div class="{{($bookings->count()) ? 'col-lg-7' : 'col-lg-12'}} "> -->
                        @include('front.agent._partials.student-graph')
                        <!-- </div> -->

                            @if ($bookings->count())
                                <div class="col-lg-5">
                                    @include('front.agent._partials.agent-bookings')
                                </div>
                        @endif
                        <!-- </div>
					</div> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
