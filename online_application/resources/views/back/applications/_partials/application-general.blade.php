<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'title',
        'label' => 'Title' ,
        'class' =>'' ,
        'value' => $application->title,
        'required' => true,
        'attr' => ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'layout',
        'label' => 'Layout' ,
        'class' =>'' ,
        'value' => $application->layout,
        'required' => true,
        'attr' => 'onchange=app.getThemeCustomization(this)',
        'data' => ApplicationHelpers::getApplicationThemesList()
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
            'value'     => isset($application->properties['invoice']) ? $application->properties['invoice'] : '',
            'required'  => true,
            'attr'      => '',
            'data'      => ApplicationHelpers::getInvoiceCreationList()
        ])
    </div>


    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'properties[application_fees]',
        'label' => 'Registration Fees' ,
        'class' =>'' ,
        'value' => (isset($application->properties['application_fees']))? $application->properties['application_fees'] :
        '',
        'required' => false,
        'attr' => '' ,
        'hint_after' => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] :
        'CAD'
        ])
    </div>


    @include('back.layouts.core.forms.campuses', [
        'class'     => '',
        'required'  => true,
        'attr'      => '',
        'value'     => isset($application->campuses) ? $application->campuses->pluck('id')->toArray()  : '',
        'data'      => isset($campuses) ? $campuses : []
    ])

</div>

<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.checkbox',
        [
        'name' => 'properties[no_login]',
        'label' => false ,
        'class' => '' ,
        'required' => false,
        'attr' => '',
        'helper_text' => 'Disable account creation',
        'value' => (isset($application->properties['no_login']))? $application->properties['no_login'] : 0,
        'default' => 1,
        'helper' => 'Applicant can submit this applications without creating an account, Please note that they will not
        be able to save the application and resume it later'
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
           'value' => (isset($application->properties['review_page']))? $application->properties['review_page'] : 0,
        'default' => 1,
           'default'       =>  1,
           'helper'        => 'Enabling review page will lock the application after submission'
       ])
   </div>



    <div class="col-md-12">
        @include('back.layouts.core.forms.checkbox',
        [
        'name' => 'properties[enable_for_agents]',
        'label' => false ,
        'class' => '' ,
        'required' => false,
        'attr' => '',
        'helper_text' => 'Enable application for agents',
        'value' => (isset($application->properties['enable_for_agents'])) ?
        $application->properties['enable_for_agents'] : 0,
        'default' => 1,
        ])
    </div>

{{--    <div class="col-md-12">--}}
{{--        @include('back.layouts.core.forms.checkbox',--}}
{{--        [--}}
{{--        'name' => 'properties[disable_resubmission]',--}}
{{--        'label' => false ,--}}
{{--        'class' => '' ,--}}
{{--        'required' => false,--}}
{{--        'attr' => '',--}}
{{--        'helper_text' => 'Disable application resubmission',--}}
{{--        'value' => (isset($application->properties['disable_resubmission'])) ?--}}
{{--        $application->properties['disable_resubmission'] :--}}
{{--        0,--}}
{{--        'default' => 1,--}}
{{--        ])--}}
{{--    </div>--}}

    <div class="col-md-12">
        @include('back.layouts.core.forms.checkbox',
        [
            'name'          => 'properties[multiple_submissions]',
            'label'         => false ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Enable multiple application submissions',
            'value'         => (isset($application->properties['multiple_submissions'])) ?
        $application->properties['multiple_submissions'] :
        0,
            'default'       => 1,
            ])
    </div>

    <div class="col-md-12">
        @include('back.layouts.core.forms.checkbox',
        [
            'name'          => 'properties[lock_submission]',
            'label'         => false,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'helper_text'   => 'Lock submission after submit',
            'value'         => (isset($application->properties['lock_submission'])) ?
                $application->properties['lock_submission'] :
                0,
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
            'value'         => (isset($application->properties['request_edit'])) ?
                $application->properties['request_edit'] :
                0,
            'default'       => 1,
            'helper'    => 'Allow user to request edit if the application is lock'
            ])
    </div>

</div>


<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-area',
        [
        'name' => 'description',
        'label' => 'Description' ,
        'class' =>'' ,
        'value' => $application->description,
        'required' => true,
        'attr' => ''
        ])
    </div>

</div> <!-- row -->
