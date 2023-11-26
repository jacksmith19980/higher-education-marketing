@component('mail::agent-message')
# Password Reset

Hi {{$student->first_name}},

We received a request to reset the password for {{$student->email}}. Please visit the link below to choose a new password.

@component('mail::button', ['url' => route('school.reset.password' , ['school' => $school , 'token' => $token , 'email' => $student->email])])
Choose a new password
@endcomponent

If you received this message in error, please do nothing and your password will remain the same.

Thanks,<br>
{{ $school->name }}
@endcomponent