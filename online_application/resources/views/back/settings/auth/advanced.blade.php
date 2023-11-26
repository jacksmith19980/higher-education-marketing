
<div class="card no-padding card-border">
    <div class="card-header">
        <h4 class="card-title">{{__('Login Page/Look and Feel')}}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                @include('back.layouts.core.forms.html',
                [
                'name' => 'login_title_text',
                'label' => 'Title Text' ,
                'class' => '' ,
                'required' => false,
                'attr' => $disabled,
                'value' => isset($settings['auth']['login_title_text']) ?
                $settings['auth']['login_title_text'] : ''
                ])
            </div>
            <!-- login page side description text -->
            <div class="col-md-12">
                @include('back.layouts.core.forms.html',
                [
                'name' => 'login_read_more_text',
                'label' => 'Read More Text' ,
                'class' => '' ,
                'required' => false,
                'attr' => $disabled,
                'value' => isset($settings['auth']['login_read_more_text']) ?
                $settings['auth']['login_read_more_text'] : ''
                ])
            </div>
            <div class="col-md-12">
                <ul class="list-unstyled">
                    <li class="media">
                        @if (isset($settings['auth']['login_background']))
                        <img class="d-flex m-r-15"
                            src="{{ Storage::disk('s3')->temporaryUrl($settings['auth']['login_background']['path'], \Carbon\Carbon::now()->addMinutes(5)) }}"
                            alt="Generic placeholder image" width="140">
                        @endif
                        <div class="media-body">
                            <div class="form-group">
                                @include('back.layouts.core.forms.file-input', [
                                'name' => 'login_background',
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
        </div>
    </div>
</div>

<div class="card no-padding card-border">
    <div class="card-header">
        <h4 class="card-title">{{__('Registeration Page/Look and Feel')}}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- register page side title text -->

            <div class="col-md-12">
                @include('back.layouts.core.forms.html',
                [
                'name' => 'register_title_text',
                'label' => 'Title Text' ,
                'class' => '' ,
                'required' => false,
                'attr' => $disabled,
                'value' => isset($settings['auth']['register_title_text']) ?
                $settings['auth']['register_title_text'] : ''
                ])
            </div>

            <!-- register page side description text -->
            <div class="col-md-12">
                @include('back.layouts.core.forms.html',
                [
                'name' => 'register_read_more_text',
                'label' => 'Read More Text' ,
                'class' => '' ,
                'required' => false,
                'attr' => $disabled,
                'value' => isset($settings['auth']['register_read_more_text']) ?
                $settings['auth']['register_read_more_text'] : ''
                ])
            </div>
            <div class="col-md-12">
                <ul class="list-unstyled">
                    <li class="media">
                        @if (isset($settings['auth']['register_background']))
                        <img class="d-flex m-r-15"
                            src="{{ Storage::disk('s3')->temporaryUrl($settings['auth']['register_background']['path'], \Carbon\Carbon::now()->addMinutes(5)) }}"
                            alt="Generic placeholder image" width="140">
                        @endif
                        <div class="media-body">
                            <div class="form-group">
                                @include('back.layouts.core.forms.file-input', [
                                'name' => 'register_background',
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
        </div>
    </div>
</div>
