@component('mail::agent-message')
# Your account is created

Hi {{$parent->first_name}},

Your password is: {{$password}} 

@component('mail::button', ['url' => route('school.home' , $school)])
Visit Your account
@endcomponent

Thanks,<br>
{{ $school->name }}
@endcomponent
