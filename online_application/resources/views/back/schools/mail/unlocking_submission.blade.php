@component('mail::agent-message')
#Thanks for submitting your application to {{$school->name}}.

We noticed you requested to unlock your application submission and just wanted to let you know that your application form is now unlocked and you can go in anytime to edit or update your information. To access your form, login [here]({{$settings['school']['domain']}})

Thanks for considering us.

The {{$school->name}} team
@endcomponent
