<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'settings[mail][provider]',
        'label' => '3rd Party Mailing' ,
        'class' => 'ajax-form-field' ,
        'required' => false,
        'attr' => "onchange=app.loadContent(this,'profile.updateMailProvider',". json_encode(['backEnd' => true]) .",'mailProviderSettings') ",
        'data' => [
                'none'      => 'Don\'t connect my email account',
                'gmail'     => 'Gmail or G-Suite',
                'microsfot' => 'Hotmail or Outlook',
        ] ,
        'value' => (isset($user->settings['mail']['provider'])) ? $user->settings['mail']['provider'] : 'none'
        ])
    </div>
</div>
<div class="row" id="mailProviderSettings">

    @if(isset($user->settings['mail']['provider']))
        @include('back.profile._partials.' . $user->settings['mail']['provider'])
    @endif

</div>
