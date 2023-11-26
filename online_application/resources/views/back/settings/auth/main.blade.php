
<div class="col-md-10">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('Look and Feel')}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-unstyled">
                        <li class="media">
                            @if (isset($settings['auth']['background']))
                            <img class="d-flex m-r-15"
                                src="{{ Storage::disk('s3')->temporaryUrl($settings['auth']['background']['path'], \Carbon\Carbon::now()->addMinutes(5)) }}"
                                alt="Generic placeholder image" width="140">
                            @endif
                            <div class="media-body">
                                <div class="form-group">
                                    @include('back.layouts.core.forms.file-input', [
                                    'name' => 'background',
                                    'label' => 'Background Image' ,
                                    'class' => 'settings_login_background',
                                    'required' => false,
                                    'attr' => $disabled,
                                    'value' => '',
                                    ])
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- -->
                <div class="col-md-6">
                    @php
                        $layout = isset($settings['auth']['login_type']) ? $settings['auth']['login_type'] :
                    'Basic';
                    if($layout == 'Enhance'){
                        $layout = 'Enhanced';
                    }
                    @endphp

                    @include('back.layouts.core.forms.select',
                    [
                    'name' => 'login_type',
                    'label' => 'Login\Register Page' ,
                    'class' => '',
                    'required' => false,
                    'attr' => "onchange=app.loadAuthLayout(this) $disabled",
                    'helper_text' => '',
                    'data' => [
                        'Basic'      => 'Basic',
                        'Enhanced'   => 'Enhanced',
                        'Advanced'   => 'Advanced'
                    ],
                    'value' => $layout,
                    ])
                </div>
            </div>
            <div class="row" id="auth-layout-container">
                @include('back.settings.auth.' . strtolower($layout) , ['disabled' => $disabled])

            </div>

        </div>
    </div>
</div>
