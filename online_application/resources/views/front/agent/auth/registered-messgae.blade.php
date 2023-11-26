<p class="alert alert-warning">
    {{__('This email is already registered')}}
    <a href="{{route('school.agent.register.step2', ['school'=> $school , 'agency' => $agency])}}">{{__('Click here')}}</a> {{__('to complete your agency registration!')}}
</p>
