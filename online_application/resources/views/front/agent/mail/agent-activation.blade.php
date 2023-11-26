@component('mail::agent-message')
# Account Activation

Hi {{$agent->first_name}},

{{__('Your account has been created successfully, Please click on the below button to activate your account')}}.

@component('mail::button', ['url' => route('school.agent.activate' , [ 'school' => $school, 'token' => $agent->activation_token , 'email' => $agent->email ])])
Activate Now

@endcomponent

Thanks,<br>
{{ $school->name }}
@endcomponent