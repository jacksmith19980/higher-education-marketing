@extends('front.layouts.agents')

@section('content')
	<div class="page-wrapper" style="padding-top: 100px;min-height:95vh">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info m-b-10 p-a-20">
                            <h4 class="text-white">{{__('My Account')}}</h4>
                        </div>

                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-lg-4 col-xl-3">
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        
                                        <a class="nav-link active" id="v-pills-account-details-tab" data-toggle="pill" href="#v-pills-account-details" role="tab" aria-controls="v-pills-account-details" aria-selected="true">{{__('Account Details')}}</a>

                                    </div>
                                </div>
                                <div class="col-lg-8 col-xl-9">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        
                                        <div class="tab-pane fade show active" id="v-pills-account-details" role="tabpanel" aria-labelledby="v-pills-account-details-tab">
                                        
                                        
                                            @include('front.agent.account.account-details')
                                           


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
@endsection


