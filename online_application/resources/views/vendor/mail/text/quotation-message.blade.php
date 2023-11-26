@component('mail::layout')

{{-- Header --}}

@slot('header')

@component('mail::header', ['url' =>  route('school.login' , $school)  ])

{{$school->name}}

@endcomponent

@endslot



{{-- Body --}}

{{ $slot }}



{{-- Subcopy --}}

@isset($subcopy)

@slot('subcopy')

@component('mail::subcopy')

{{ $subcopy }}

@endcomponent

@endslot

@endisset



{{-- Footer --}}

@slot('footer')

@component('mail::footer')

© {{ date('Y') }} {{ $school->name }}. All rights reserved.

@endcomponent

@endslot

@endcomponent

