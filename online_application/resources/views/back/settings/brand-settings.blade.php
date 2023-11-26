@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp
<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
        enctype="multipart/form-data">

    <div class="row">
        @csrf
        @include('back.layouts.core.forms.hidden-input', [
            'name' => 'group',
            'value' => 'branding',
            'class' => '',
            'required' => '',
            'attr' => $disabled,
        ])
        <input type="hidden" name="action" value="add_branding" />
        {{--Branding--}}
        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Branding')}}</h4>
                </div>
                <div class="card-body" style="border:1px solid #f7f7f7;">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-unstyled">
                                <li class="media">

                                    @if (isset($settings['branding']['logo']))
                                        <img class="d-flex m-r-15"
                                            src="{{ Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5)) }}"
                                            alt="Generic placeholder image" width="120">
                                    @endif

                                    <div class="media-body">
                                        <div class="form-group">
                                            @include('back.layouts.core.forms.file-input', [
                                                'name' => 'logo',
                                                'label' => 'Logo' ,
                                                'class' => 'settings_logo',
                                                'required' => false,
                                                'attr'      => $disabled,
                                                'value'     => '',
                                            ])
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-12">
                            <ul class="list-unstyled">
                                <li class="media">
                                    @if (isset($settings['branding']['icon']))
                                        <img class="d-flex m-r-15"
                                                src="{{ Storage::disk('s3')->temporaryUrl($settings['branding']['icon']['path'], \Carbon\Carbon::now()->addMinutes(5)) }}"
                                                alt="Generic placeholder image" width="120">
                                    @endif

                                    <div class="media-body">
                                        <div class="form-group">
                                            @include('back.layouts.core.forms.file-input', [
                                                'name' => 'icon',
                                                'label' => 'Icon' ,
                                                'class' => '',
                                                'required' => false,
                                                'attr' => $disabled,
                                                'value' => '',
                                            ])
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'main_color',
                                'label' => 'Buttons Color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => $disabled,
                                'value' => isset($settings['branding']['main_color'])? $settings['branding']['main_color'] : '',
                                'helper_text' => 'Used for buttons'
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'secondary_color',
                                'label' => 'Secondary Color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => $disabled,
                                'value' => isset($settings['branding']['secondary_color'])? $settings['branding']['secondary_color'] : '',
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'links_color',
                                'label' => 'Links Color' ,
                                'class' => '',
                                'required' => false,
                                'attr' => $disabled,
                                'value' => isset($settings['branding']['links_color'])? $settings['branding']['links_color'] : '',
                                'helper_text' => 'Used for links'
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('back.layouts.core.forms.color-input', [
                                'name' => 'background_color',
                                'label' => 'Back ground' ,
                                'class' => '',
                                'required' => false,
                                'attr' => $disabled,
                                'value' => isset($settings['branding']['background_color'])? $settings['branding']['background_color'] : '',
                                'helper_text' => 'Used for Back ground'
                            ])
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('back.settings.seo-settings' , ['disabled' => $disabled])

        @include('back.schools._partials.add-logos' , ['disabled' => $disabled])

        <div class="col-md-10">
            <button data-name="settings_branding_save_button"  {{$disabled}}
                class="float-right btn btn-success" onclick="app.addLogos()">{{__('Save Logos')}}</button>
        </div>
    </div>
    <div id="brand_settings_div"></div>
</form>
