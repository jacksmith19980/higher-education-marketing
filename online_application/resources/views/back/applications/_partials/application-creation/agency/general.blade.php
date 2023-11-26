<h6>{{__("Application")}}</h6>
<section>
    <div class="row">



        <div class="col-md-6">
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


            @include('back.layouts.core.forms.hidden-input',
            [
                'name'      => 'layout',
                'label'     => 'Layout' ,
                'class'     =>'' ,
                'value'     => 'agency',
                'required'  => true,
                'attr'      => ''
            ])
        </div>


        <div class="col-md-6">
            @include('back.layouts.core.forms.select',
            [
                'name'      => 'properties[show_in]',
                'label'     => 'Show Aplication in' ,
                'class'     =>'' ,
                'value'     => '',
                'required'  => true,
                'attr'      => '',
                'data'      => [
                    'settings'      => 'Agency Settings Page',
                    'registration' => 'Agency Registeration Page',
                ]
            ])
        </div>
        @include('back.layouts.core.forms.campuses', [
            'class'     => '',
            'required'  => true,
            'attr'      => '',
            'value'     => '',
            'data'      => $campuses
        ])

        <div class="col-md-12">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'properties[disable_resubmission]',
                'label'         => false ,
                'class'         => '' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Disable application resubmission',
                'value'         => 0,
                'default'       => 1,
                ])
        </div>

    </div>
    <div class="row">
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
