<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',[
        'name'          => 'properties[pricing][regular_price]',
        'label'         => 'Regular Price' ,
        'class'         => '' ,
        'required'      => true,
        'attr'          => '',
        'data'         => '',
        'value'          => (isset($program->properties['pricing']['regular_price'])) ? $program->properties['pricing']['regular_price'] : '',
        'hint_after'    => 'CAD'
    ])
</div>