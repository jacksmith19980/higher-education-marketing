@component('mail::message')
# You are invited to join {{$school->name}}'s Team

Hi {{$data['name']}},

You are invited to join {{$school->name}}'s HEM-SP.

@if (isset($data['password']))

You can now log in to your account using this password: **{{ $data['password'] }}**

Click on the link below to activate your account and log in!

@component('mail::button', ['url' =>  route('activate.user.account' , [
    'email' => $data['email'],
    'token' => isset($data['activation_token']) ? $data['activation_token'] : $data['token'],
]) ])
    Activate your account
@endcomponent

@else

Please log in to you account and select {{$school->name}}

@endif


Thanks,<br>
{{ $school->name }}
@endcomponent
