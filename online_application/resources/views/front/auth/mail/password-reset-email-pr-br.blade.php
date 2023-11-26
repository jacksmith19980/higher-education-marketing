@component('mail::agent-message')
# Redefinição de senha

Hi {{$student->first_name}},

Recebemos uma solicitação para redefinir a senha de {{$student->email}}.
Acesse o link abaixo para escolher uma nova senha.

@component('mail::button', ['url' => route('school.reset.password' , ['school' => $school , 'token' => $token , 'email' => $student->email])])
Escolha uma nova senha
@endcomponent

Se você recebeu esta mensagem por engano, não faça nada e sua senha permanecerá a mesma.

Muito obrigado,<br>
{{ $school->name }}
@endcomponent
