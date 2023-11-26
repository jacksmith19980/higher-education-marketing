@if (count($application->invoices) ||
( $application->status != 'Submitted' && !$application->onTimeSubmission && !empty( trim( $application->status)))
)

<div class="btn-group">

    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <span class="ti-settings"></span>
    </button>

    <div class="dropdown-menu">

        @if (isset($application->properties['show_instructions']) && $application->properties['show_instructions'] == 1)
        <a class="dropdown-item" href="javascript:void(0)" onclick="app.startModal(this)"
            data-route='{{route("application.instructions" , ["school"=>$school , "application" => $application])}}'
            data-title="Application's Instructions">
            {{__('Instructions')}}
        </a>
        @endif

        
        @if ( $application->status == 'Submitted' && !$application->onTimeSubmission )
        <a class="dropdown-item"
            href="{{route('application.show' , ['school' => $school , 'application' => $application ])}}">
            {{__('Edit your application')}}
        </a>
        @endif

        @if ( in_array($application->status , ['New']) )
        <a class="dropdown-item"
            href="{{route('application.show' , ['school' => $school , 'application' => $application ])}}">
            {{__('Submit your application')}}
        </a>
        @endif
        @if ( in_array($application->status , ['Updated' , 'Started']) )
        <a class="dropdown-item"
            href="{{route('application.show' , ['school' => $school , 'application' => $application ])}}">
            {{__('Edit your application')}}
        </a>
        @endif

        @if (count($application->invoices) && !optional($application->invoices->first())->paid )

        <div class="dropdown-divider"></div>

        <a class="dropdown-item"
            href="{{route( 'show.payment' , ['school'=>$school , 'application' => $application , 'invoice' => $application->invoices->first() ] )}}">{{__('Pay fees')}}</a>

        @endif
    </div>
</div>

@endif