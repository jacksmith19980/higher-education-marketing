@if (count($child->submissions))
    

<div class="btn-group">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{__('Applications')}}
    </button>
    <div class="dropdown-menu">
        
        {{-- @foreach ($bookings as $booking)

            <a class="dropdown-item bg-sucess" href="{{route('school.parent.child.submit' , ['school' => $school , 'application' => $booking->quotation->application , 'student' => $child ])}}">
                
                    @php
                        $childSubmissions = $booking->quotation->application->submissions()->pluck('student_id')->toArray();
                    @endphp

                    @if ( in_array($child->id , $childSubmissions) ) 
                    
                        <span class="ti-check text-success"></span>
                        
                    @else 
                        
                        <span class="ti-check text-danger"></span>

                    @endif
                
                
                {{$booking->quotation->title}} - ( {{$booking->invoice['totalPrice']}}{{$settings['school']['default_currency']}} )
            </a>
        @endforeach --}}

        @foreach ($child->submissions as $submission)
            <a class="dropdown-item bg-sucess" href="{{route('school.parent.child.submit' , ['school' => $school , 'application' => $submission->application , 'student' => $child ])}}">

                <span class="ti-check text-success"></span>
                {{$submission->application->title}}

            </a>
        @endforeach


    </div>
</div>


@else

    <span class="btn btn-danger">{{__('No Applications')}}</span>

@endif