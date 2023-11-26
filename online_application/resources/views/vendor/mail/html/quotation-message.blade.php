@component('mail::layout')

{{-- Header --}}

@slot('header')

@component('mail::header', ['url' =>  route('school.login' , $school)  ])
@if (isset($settings['branding']['logo']))
<img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="school_logo"
style="max-width: 80%;" />
@else

{{$school->name}}

@endif


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
