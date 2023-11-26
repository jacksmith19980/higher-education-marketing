@component('mail::agent-message')
# Password Reset

Salut {{$student->first_name}},

Nous avons reçu une demande de réinitialisation du mot de passe pour {{$student->email}}. Veuillez visiter le lien ci-dessous pour choisir un nouveau mot de passe.

@component('mail::button', ['url' => route('school.reset.password' , ['school' => $school , 'token' => $token , 'email' => $student->email])])
Choisissez un nouveau mot de passe
@endcomponent

Si vous avez reçu ce message par erreur, ne faites rien et votre mot de passe restera le même.

Merci,<br>
{{ $school->name }}
@endcomponent