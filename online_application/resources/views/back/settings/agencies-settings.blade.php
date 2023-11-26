@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp

<h4 class="m-b-15">{{__('Recruiters Hub Settings')}}</h4>
<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
    enctype="multipart/form-data">
    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input',
        [
        'name' => 'group',
        'label' => '' ,
        'class' => '',
        'required' => false,
        'attr' => $disabled,
        'value' => 'agencies',
        ])
    </div>
    {{-- General Settinhs Block --}}
    @include('back.settings.agencies.general' , ['disabled' => $disabled])

    {{-- Mautic Settings --}}
    @if (in_array('mautic' , $crm))
    @include('back.settings.agencies.mautic' , [
    'settings' => $settings,
    'disabled' => $disabled
    ])
    @endif



    <div class="col-md-12">
        <button class="float-right btn btn-success" {{$disabled}}>
            {{__('Save')}}
        </button>
    </div>

</form>
