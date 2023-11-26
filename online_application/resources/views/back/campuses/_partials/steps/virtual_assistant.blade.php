<h6>Campus Virtual Assistant</h6>
<section>
    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.html', [
                'name' => 'properties[short_description]',
                'label' => 'Short Description' ,
                'class' => '',
                'required' => false,
                'attr' => '',
                'value' => ''
            ])
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.html', [
                'name' => 'details',
                'label' => 'Details' ,
                'class' => '',
                'required' => false,
                'attr' => '',
                'value' => ''
            ])
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'properties[campus_location]',
                'label'     => 'Campus Location' ,
                'class'     => '',
                'required'  => false,
                'attr'      => '',
                'value'     => ''
            ])
        </div>
    </div> <!-- row -->

    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'properties[video]',
                'label'     => 'Video' ,
                'class'     => '',
                'required'  => false,
                'attr'      => '',
                'value'     => ''
            ])
        </div>
    </div> <!-- row -->

    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.file-input', [
                'name'     => 'featured_image',
                'label'    => 'Featured Image' ,
                'class'    => '',
                'required' => false,
                'attr'     => '',
                'value'    => '',
            ])
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.text-area',
            [
                'name'      => 'properties[virtual_tour]',
                'label'     => '360 Virtual Tour' ,
                'class'     => '',
                'required'  => false,
                'attr'      => '',
                'value'     => ''
            ])
        </div>
    </div> <!-- row -->
</section>