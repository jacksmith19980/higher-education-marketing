@if ($child->submissions->count())
    @foreach ($child->submissions as $submission)
        <a class="btn btn-small btn-info " href="{{route('school.parent.child.submit' , ['school' => $school , 'application' => $submission->application , 'student' => $child ])}}">
            {{$submission->application->title}}
        </a>
    @endforeach
@endif