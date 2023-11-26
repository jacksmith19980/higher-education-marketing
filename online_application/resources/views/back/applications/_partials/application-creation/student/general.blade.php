<h6>{{__("Application")}}</h6>
<section>
    <div class="row">
        <div data-name="add_application_title" class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'title',
                'label'     => 'Title' ,
                'class'     =>'' ,
                'value'     => '',
                'required'  => true,
                'attr'      => ''
            ])

            @include('back.layouts.core.forms.hidden-input',
            [
                'name'      => 'object',
                'label'     => 'Object' ,
                'class'     =>'' ,
                'value'     => $object,
                'required'  => true,
                'attr'      => ''
            ])
        </div>

        <div data-name="add_application_layout" class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'layout',
                'label'     => 'Layout' ,
                'class'     => 'application_layout',
                'value'     => '',
                'required'  => true,
                'attr'      => 'onchange=app.getThemeCustomization(this)',
                'data'      => ApplicationHelpers::getApplicationThemesList()
            ])
        </div>

    </div>

    <div class="row">

        <div  class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'properties[invoice]',
                'label'     => 'Registration Invoice' ,
                'class'     => '',
                'value'     => '',
                'required'  => true,
                'attr'      => '',
                'data'      => ApplicationHelpers::getInvoiceCreationList()
            ])
        </div>



        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'properties[application_fees]',
                'label'     => 'Registration Fees' ,
                'class'     =>'' ,
                'value'     => '',
                'required'  => false,
                'attr'      => '' ,
                'hint_after'    => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'
            ])
        </div>

        @include('back.layouts.core.forms.campuses', [
            'class'     => '',
            'required'  => true,
            'attr'      => '',
            'value'     => '',
            'data'      => $campuses
        ])



    </div>

    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.checkbox',
            [
              'name'            => 'properties[no_login]',
                'label'         => false ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Login is not required',
                'value'         =>  0,
                'default'       =>  1,
                'helper'        => 'Applicant can submit this applications without creating an account, Please note that they will not be able to save the application and resume it later'
            ])
        </div>
        <div class="col-md-12">
             @include('back.layouts.core.forms.checkbox',
            [
              'name'            => 'properties[review_page]',
                'label'         => false ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Enable review page',
                'value'         =>  0,
                'default'       =>  1,
                'helper'        => ''
            ])
        </div>

        <div class="col-md-12">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[enable_review_step]',
                'label'         => false ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Show review step',
                'value'         =>  0,
                'default'       => 1,
                ])
        </div>


        <div class="col-md-12">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[enable_for_agents]',
                'label'         => false ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Enable Application for Agents',
                'value'         =>  0,
                'default'       => 1,
                ])
        </div>

{{--        <div class="col-md-12">--}}
{{--            @include('back.layouts.core.forms.checkbox',--}}
{{--            [--}}
{{--                'name'          => 'properties[disable_resubmission]',--}}
{{--                'label'         => false ,--}}
{{--                'class'         => '' ,--}}
{{--                'required'      => false,--}}
{{--                'attr'          => '',--}}
{{--                'helper_text'   => 'Disable application resubmission',--}}
{{--                'value'         => 0,--}}
{{--                'default'       => 1,--}}
{{--                ])--}}
{{--        </div>--}}

        <div class="col-md-12">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[multiple_submissions]',
                'label'         => false ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Enable multiple application submissions',
                'value'         => 0,
                'default'       => 1,
                ])
        </div>

        <div class="col-md-12">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[lock_submission]',
                'label'         => false ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Lock submission after submit',
                'value'         => 1,
                'default'       => 1,
                ])
        </div>

        <div class="col-md-12">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[request_edit]',
                'label'         => false ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Request Edit',
                'value'         => 0,
                'default'       => 1,
                'helper'    => 'Allow user to request edit if the application is lock'
                ])
        </div>
    </div>

    <div data-name="add_application_description" class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.text-area',
            [
                'name'  => 'description',
                'label' => 'Description' ,
                'class' =>'' ,
                'value'     => '',
                'required'  => true,
                'attr'  => ''
            ])
        </div>
    </div> <!-- row -->
</section>
