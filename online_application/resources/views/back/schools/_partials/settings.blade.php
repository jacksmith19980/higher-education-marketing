<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
        'name' => 'settings[school][locale]',
        'label' => 'Language' ,
        'class' => '',
        'required' => true,
        'attr' => '',
        'value' => isset($settings['school']['locale'])? $settings['school']['locale'] :
        "en",
        'data' => [
        "en" => 'English',
        "fr" => 'French',
        "gr" => 'German',
        "es" => "Spanish",
        ]
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
        'name' => 'settings[school][website]',
        'label' => 'Website' ,
        'class' => '',
        'required' => false,
        'attr' => '',
        'value' => isset($settings['school']['website'])? $settings['school']['website'] : '',
        'placeholder' => 'http://',
        ])
    </div>
</div>
