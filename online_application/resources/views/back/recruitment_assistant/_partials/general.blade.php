<div class="row">
    @if (!count($courses) && !count($programs))
        <div class="alert alert-danger col-12">
            {{ __('You don\'t have any courses or program, Please add one of them first') }}
        </div>
    @endif
</div>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.text-input', [
                'name'      => 'title',
                'label'     => 'Title' ,
                'class'     =>'' ,
                'required'  => true,
                'attr'      => '',
                'value'     => (isset($assistantBuilder->title)) ? $assistantBuilder->title : '',
                'data'      => ''
            ])
        </div>

        <div class="col-md-12">
            @include('back.layouts.core.forms.select',
                [
                    'name'      => 'application',
                    'label'     => 'Application' ,
                    'class'     =>'select2' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => (isset($assistantBuilder->application_id)) ? $assistantBuilder->application_id : '',
                    'placeholder' => 'Select Application',
                    'data'      => $applications
                    ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
            'name' => 'help_title',
            'label' => 'Help title' ,
            'class' =>'' ,
            'required' => false,
            'attr' => '',
            'value' => (isset($assistantBuilder->help_title)) ? $assistantBuilder->help_title : '',
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
            'name' => 'help_content',
            'label' => 'Help text' ,
            'class' =>'' ,
            'required' => false,
            'attr' => '',
            'value' => (isset($assistantBuilder->help_content)) ? $assistantBuilder->help_content : '',
            ])
        </div>

        <div class="col-md-12">

            <ul class="list-unstyled">
                <li class="media">

                    @if ( isset($assistantBuilder->help_logo) )
                    <img class="d-flex m-r-15" src="{{ Storage::disk('s3')->temporaryUrl($assistantBuilder->help_logo['path'], \Carbon\Carbon::now()->addMinutes(5)) }}"
                        alt="Generic placeholder image" width="120">
                    @endif

                    <div class="media-body">
                        <div class="form-group">
                            @include('back.layouts.core.forms.file-input', [
                                'name' => 'help_logo',
                                'label' => 'Helper Icon' ,
                                'class' => '',
                                'required' => false,
                                'attr' => '',
                                'value' => '',
                            ])
                        </div>
                    </div>
                </li>
            </ul>

           {{--  @include('back.layouts.core.forms.file-input', [
                'name'     => 'help_logo',
                'label'    => 'Help Icon' ,
                'class'    => '',
                'required' => false,
                'attr'     => '',
                'value'    => '',
            ]) --}}
        </div>

        <div class="row w-100" id="quotation-draggable-area">
            @include('back.recruitment_assistant._partials.thank_you.index')
        </div>

        @if(isset($settings['integrations']))
            <div class="row w-100" id="quotation-draggable-area">
                @if(isset($settings['integrations']['integration_mautic']))
                    @include('back.recruitment_assistant._partials.integrations.mautic.index')
                @endif
            </div>
        @endif

    </div>
</div>
<br>
