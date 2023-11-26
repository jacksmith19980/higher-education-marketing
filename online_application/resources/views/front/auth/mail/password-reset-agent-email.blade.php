@component('mail::agent-message')
# Password Reset

Hi {{$agent->first_name}},

We received a request to reset the password for {{$agent->email}}. Please visit the link below to choose a new password.

@component('mail::button', ['url' => route('school.agent.reset.password' , ['school' => $school , 'token' => $token , 'email' => $agent->email])])
Choose a new password
@endcomponent

If you received this message in error, please do nothing and your password will remain the same.

Thanks,<br>
{{ $school->name }}
@endcomponent