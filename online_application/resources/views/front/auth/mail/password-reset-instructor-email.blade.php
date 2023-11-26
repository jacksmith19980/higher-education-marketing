@component('mail::instructor-message')
# Password Reset

Hi {{$instructor->first_name}},

We received a request to reset the password for {{$instructor->email}}. Please visit the link below to choose a new password.

@component('mail::button', ['url' => route('school.instructor.reset.password', ['school' => $school ,'token' => $token ,'email' => $instructor->email])])
Choose a new password
@endcomponent

If you received this message in error, please do nothing and your password will remain the same.

Thanks,<br>
{{ $school->name }}
@endcomponent