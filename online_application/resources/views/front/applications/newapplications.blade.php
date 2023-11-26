@extends('front.layouts.minimal')
@section('content')

<div class="page-wrapper" style="padding-top: 100px;">
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header" style="background-color: #fff;">
                                <h4 class="">{{__('New Application Forms')}}</h4>
                            </div>
                        </div>
                        <div class="card box-shadow h-100">
                            <div class="card-body px-4 py-3">
                                <!-- <h3 class="card-title">Other Application</h3> -->
                                @foreach ($studentApplications as $application)
                                    @if(!($application->status) || (isset($application->properties['multiple_submissions']) && $application->properties['multiple_submissions'] == 1))
                                        <div class="d-flex justify-content-between flex-nowrap mb-3">
                                            <div class="pr-2"><i class="fas fa-caret-right text-primary mr-1 list-indicator"></i>{{ $application->title }}</div>
                                            <div><a class="btn btn-primary btn-sm mbtn mb-ms-2 is-uppercase" href="{{route('application.show' , ['school' => $school , 'application' => $application ])}}">{{__('Start')}}</a></div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6 mb-4">
                        <div class="card box-shadow">
                            <div class="card-body px-4 py-3">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title">Payments</h3>
                                  <div class="d-flex justify-content-center my-auto">
                                     <a href="#" class="btn btn-primary px-4 is-uppercase">Make a payment</a>
                                  </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

    </div>
</div>



@endsection
