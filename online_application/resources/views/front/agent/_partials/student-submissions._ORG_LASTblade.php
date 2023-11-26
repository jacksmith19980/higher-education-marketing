@if ($applications->count())

    {{--  @dump($applications)  --}}

    @php
        $submittedApplication = $agentStudent->submissions()->with('application')->get()->pluck('application.id')->toArray();
    @endphp

    <div class="btn-group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{__('Applications')}}
        </button>

        <div class="dropdown-menu">

        @foreach ($applications as $application)
            
            <a class="dropdown-item" href="{{route('school.agent.student.submit' , ['school' => $school , 'application' => $application , 'student' => $agentStudent ])}}">
                
                @if (in_array( $application->id , $submittedApplication ))
                    <span class="m-r-10 mdi mdi-check text-success"></span>
                @else        
                    <span class="m-r-10 mdi mdi-check text-danger"></span>
                @endif

            {{$application->title}}</a>
        @endforeach		
        </div>
    </div>
@endif

