<h6>{{__("Application")}}</h6>
<section>
    <div class="row">
        <div data-name="add_application_title" class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'title',
                'label'     => 'Title' ,
                'class'     => '',
                'value'     => '',
                'required'  => true,
                'attr'      => ''
            ])

            @include('back.layouts.core.forms.hidden-input',
            [
                'name'      => 'object',
                'label'     => 'Object' ,
                'class'     => '',
                'value'     => $object,
                'required'  => false,
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
                'placeholder' => 'Select Layout',
                'data'      => ['iframe' => 'Iframe']
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

    <input type="hidden" name="properties[no_login]" value="1">

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
