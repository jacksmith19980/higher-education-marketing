<div class="col-md-10">
    <div class="card no-padding card-border">
        <div class="card-header">
            <h4 class="card-title">{{__('Logos per Language')}}</h4>
        </div>
        <div class="card-body" style="border:1px solid #f7f7f7;">

            <div class="row">
                <div class="col-md-2 offset-9 m-b-30">
                    <button class="btn waves-effect waves-light btn-success" id="logos_information" {{$disabled}} type="button"
                            onclick="app.repeat_fields_new(this)"><i class="fa fa-plus"></i> {{__('Add New Logo!')}}
                    </button>

                </div>
            </div>

        @if(isset($settings['branding']['logos']))
            @foreach($settings['branding']['logos'] as $key => $value)

                <!-- <div class="row repeated_fields_new logo-row" id="logos_information" style="border:1px solid #ddd; padding: 10px; margin-bottom: 10px;"> -->
                    <div class="row repeated_fields_new logo-row" id="logos_information"
                            style="border:1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                        <div class="col-md-12">
                            <div class="form-group">

                                <ul class="list-unstyled">
                                    <li class="media">
                                        <img class="d-flex m-r-15" id="logo_img"
                                                src="{{ Storage::disk('s3')->temporaryUrl($settings['branding']['logos'][$key]['path'], \Carbon\Carbon::now()->addMinutes(5)) }}"
                                                alt="Generic placeholder image" width="120">
                                        <div class="media-body" style="padding-top: 15px;">

                                            <div class="mb-3 input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control" id="logos" name="logos[]">
                                                    <input type="hidden" name="path[]"
                                                            value="{{$settings['branding']['logos'][$key]['path'] }}">
                                                    <input type="hidden" name="name[]"
                                                            value="{{$settings['branding']['logos'][$key]['name'] }}">

                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-12 locale-select">

                            <div class="form-group">
                                @include('back.layouts.core.forms.select', [
                                'name' => 'logo_locale[]',
                                'label' => '' ,
                                'class' => '',
                                'required' => false,
                                'attr' => $disabled,
                                'value' => $key,
                                'data' => [
                                    "en" => 'English',
                                    "fr" => 'French',
                                    "gr" => 'German',
                                    "es" => "Spanish",
                                ]
                            ])
                            </div>

                        </div>

                        <div class="col-md-2 action_wrapper_new">
                            <div class="form-group action_button_new">
                                <!-- <button class="btn waves-effect waves-light btn-outline-success btn-lg" id="logos_information" type="button" onclick="app.repeat_fields_new(this)"><i class="fa fa-plus"></i></button> -->
                                <button class="btn btn-danger" {{$disabled}} type="button"
                                        onclick="app.deleteElementsRow(this, 'logo-row')">
                                    <i class="fa fa-minus"></i>
                                </button>

                            </div>
                        </div>

                    </div>
                @endforeach
            @else
                <div class="row repeated_fields_new logo-row" id="logos_information"
                        style="border:1px solid #ddd; padding: 10px; margin-bottom: 10px;">

                    <div class="col-md-12">
                        <div class="form-group">

                            <ul class="list-unstyled">
                                <li class="media">
                                    <div class="media-body" style="padding-top: 15px;">

                                        <div class="mb-3 input-group">
                                            <div class="custom-file">
                                                <input type="file" class="form-control" {{$disabled}} id="logos" name="logos[]">

                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-10">

                        <div class="form-group">

                            <select name="logo_locale[]" id="logo_locale" class="form-control form-control-lg">
                                <option value="">Language</option>
                                <option value="en">English</option>
                                <option value="fr">French</option>
                                <option value="gr">German</option>
                                <option value="es">Spanish</option>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-2 action_wrapper_new">
                        <div class="form-group action_button_new">
                            <!-- <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'logo-row')">
                                                                    <i class="fa fa-minus"></i>
                                                                </button> -->
                            <!-- <button class="btn waves-effect waves-light btn-outline-success btn-lg" id="logos_information" type="button" onclick="app.repeat_fields_new(this)"><i class="fa fa-plus"></i></button>
                     -->

                        </div>
                    </div>

                </div>
            @endif

            <div class="repeated_fields_new_wrapper" id="logos_information" data-parent="logos_information"></div>
        </div>
    </div>
</div>
