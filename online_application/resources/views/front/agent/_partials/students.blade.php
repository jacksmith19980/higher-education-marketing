@if ($students->count())

    @foreach ($students as $agentStudent)
        @include('front.agent._partials.student' , $agentStudent)
    @endforeach

@endif