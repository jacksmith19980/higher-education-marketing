@extends('front.layouts.agents')

@section('content')
	<div class="page-wrapper" style="padding-top: 100px;min-height:95vh">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        
                        <div class="card-header bg-info m-b-10 p-a-20">
                            <h4 class="text-white">{{__('Agency\'s Settings')}}</h4>
                        </div>
                       
                       
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-xl-3">
                                    <!-- Nav tabs -->
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        
                                        <a class="nav-link active" id="v-pills-general-info-tab" data-toggle="pill" href="#v-pills-general-info" role="tab" aria-controls="v-pills-general-info" aria-selected="true">{{__('General information')}}</a>

                                       
                                    @if(count($applicationsList))
                                        
                                        @foreach ($applicationsList as $application)
                                        <a class="nav-link" id="v-pills-{{$application->slug}}-tab" data-toggle="pill" href="#v-pills-{{$application->slug}}" role="tab" aria-controls="v-pills-{{$application->slug}}" aria-selected="true">{{__($application->title)}}</a>
                                       
                                        @endforeach

                                    @endif

                                    <a class="nav-link" id="v-pills-add-agents-tab" data-toggle="pill" href="#v-pills-add-agents" role="tab" aria-controls="v-pills-add-agents" aria-selected="true">{{__('Agents Managment')}}</a>


                                    </div>
                                </div>
                                <div class="col-lg-8 col-xl-9">
                                    @if (isset(session('agents')['added']))
                                    @foreach( session('agents')['added'] as $name)
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>{{$name}}</strong> {{__('Addedd Successfully to your agency!')}}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                    @endforeach
                                    @endif
            
                                    @if (isset(session('agents')['notAdded']))
                                        @foreach( session('agents')['notAdded'] as $name)
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{__('We could\'t add')}} <strong>{{$name}}</strong> {{__(' to your agency, He is registred with another agency!')}}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                        @endforeach
                                    @endif
                            
                                
                                    <div class="tab-content" id="v-pills-tabContent">

                                        @if (!$agency->approved)
                                        <div class="alert alert-warning">
                                            {{
                                                __('Please complete all your agency application forms to get you account activated')
                                            }}
                                        </div>
                                        @endif
                                        
                                        @include('front.agent.agency._partials.settings.general')
                                   
                                        @if(count($applicationsList))
                                            @foreach ($applicationsList as $application)
                                                @include('front.agent.agency._partials.settings.application' , [
                                                    'application' => $application
                                                ])
                                            @endforeach
                                        @endif

                                        @include('front.agent.agency._partials.settings.agents')



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


