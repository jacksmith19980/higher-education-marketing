@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp

<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
        enctype="multipart/form-data">
    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input', [
                    'name'          => 'group',
                    'value'         => 'education',
                    'class'         => '',
                    'required'      => '',
                    'attr'          => $disabled,
        ])




    </div>
</form>
@include('back.settings.education.degree-types' , ['disabled' => $disabled])
