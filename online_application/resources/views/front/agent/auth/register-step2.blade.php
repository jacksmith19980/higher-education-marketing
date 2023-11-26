@extends('front.layouts.auth-scripts')
@section('content')
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="logo m-t-30 m-b-30">
                        <span class="db" style="position: relative">
                            @settings(['school.logo'])
                                <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="agent_auth_logo"/>
                            @endsettings
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @foreach ($applications as $application)
                    @include('front.agent.agency._partials.settings.application' , [
                        'application'               => $application,
                        'school'                    => $school,
                        'agency'                    => $agency,
                        'registrationApplication'   => true
                    ])
                @endforeach
            </div>
        </div>
    </div>

@endsection
