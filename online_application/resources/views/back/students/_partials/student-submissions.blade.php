@php

    $submittedApplication = $student->submissions()->with('application')->get();

    $submittedApplicationIds = $submittedApplication->pluck('application.id')->toArray();

    $applications = $applications->merge($submittedApplication->pluck('application'));
    $applications = $applications->unique();
@endphp

{{-- @if ($submittedApplication->count()) --}}

<div class="btn-group">

    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false" x-placement="bottom-end">

        {{__('Applications')}}

    </button>

    <div class="dropdown-menu" style="height: 200px; overflow-y: scroll">

        @foreach ($applications as $application)

            @if (in_array( $application->id , $submittedApplicationIds ))

                <a class="dropdown-item">
{{--                <a class="dropdown-item"--}}
{{--                   href="{{route('school.agent.student.submit' ,--}}
{{--                    ['school' => $school , 'application' => $application , 'student' => $student ])}}">--}}

                        <span class="m-r-10 mdi mdi-check text-success"></span>

                        <span>

                            {{$application->title}} </br>

                            @php

                                $submission =  $student->submissions()->where('application_id' , $application->id)->first();

                            @endphp

                            <small class="d-block p-l-25">{{ __('Submitted') }}: {{$submission->created_at->diffForHumans()}}</small>

                            <small class="d-block p-l-25">{{ __('Status') }}: {{$submission->status}}</small>
                        </span>

                </a>

            @else

                <a class="dropdown-item">
{{--                <a class="dropdown-item"--}}
{{--                   href="{{route('school.agent.student.submit' , ['school' => $school , 'application' => $application , 'student' => $student ])}}">--}}

                    <span class="m-r-10 mdi mdi-close text-danger"></span>

                    <span>

                        {{$application->title}} </br>

                    </span>

                </a>

            @endif

        @endforeach

    </div>

</div>

{{-- @endif --}}
