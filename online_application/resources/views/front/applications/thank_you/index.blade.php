@extends('front.layouts.thank-you')
@section('content')

<div class="page-wrapper">

    <div class="jumbotron bt-30 d-flex justify-content-center align-items-center" style="{{ (isset($application->quotation->properties['header_image'])) ? 'background:url('.Storage::disk('s3')->temporaryUrl($application->quotation->properties['header_image']['path'], \Carbon\Carbon::now()->addMinutes(5)).') no-repeat center center' : ''}} ; background-size: cover;min-height:500px">

        @if(isset($application->quotation->properties['banner_title']))

        <h1 class="text-white display-5">{{$application->quotation->properties['banner_title']}}</h1>

        @endif


    </div>


    <div class="container-fluid">
        <div class="row">


            <div class="col-md-8 offset-md-2">
                {!! $application->quotation->properties['thank_you_copy'] !!}
            </div>

            @if( isset($invoice) && $invoice->status->last()->status != 'Paid')
                @if ( isset($application->quotation->properties['show_pay_now']) && $application->quotation->properties['show_pay_now'] && $application->paymentGateway() )

                <div class="col-md-4 offset-md-4 text-center" id="paymentContainer">
                    <div style="width: 200px; margin: 10px auto">
                        @include( 'front.applications._partials.quick-payment.'.$application->paymentGateway()->slug ,
                        [
                        'paymentGateway' => $application->paymentGateway(),
                        'booking' => $booking,
                        'invoice' => $invoice
                        ])

                    </div>
                    <small>{{__('Please note bookings are not confirmed until we have received your payment')}}</small>
                </div>
                @endif

            @endif

            <div class="col-md-8 offset-md-2 d-flex">

                <a class="btn waves-effect waves-light btn-block btn-lg btn-info text-white ml-1 mr-1 mt-5 p-3 text-uppercase" data-application="{{$application->id}}" data-booking="{{ request('booking') }}" onclick="app.addStudent('{{route('school.parent.child.create' , $school)}}' , ' ' , 'Book for a child' , this )">
                    <span class="d-block">{{__('Book for another child')}}</span>
                    <small>{{__('same date and programme')}}</small>
                </a>

                <a class="btn waves-effect waves-light btn-block btn-lg btn-info text-white ml-1 mr-1 mt-5 p-3 text-uppercase" href="{{route('quotations.show' , ['school' => $school , 'quotation' => $application->quotation ])}}">
                    <span class="d-block">{{__('Book for another child')}}</span>
                    <small>{{__('different date or programme')}}</small>

                </a>

                @if (isset($settings['school']['website']))

                <a class="btn waves-effect waves-light btn-block btn-lg btn-info text-white mr-1 ml-1 mt-5 p-3 text-uppercase d-flex align-items-center justify-content-center" onclick="event.preventDefault();
                        document.getElementById('stop-impersonate').submit(); window.location.href='{{$settings['school']['website']}}'">
                    <span class="d-block">
                        {{__('I\'m finished with my booking')}}
                    </span>
                </a>

                @endif


            </div>


        </div>

    </div>

</div>


@endsection
