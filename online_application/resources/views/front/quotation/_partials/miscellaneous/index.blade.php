@if (isset($quotation->properties['enable_misc']))
<div class="hidden quotation-extras">
    <div class="clearfix m-t-20 m-b-20"></div>
    <h5>{{__('Activities (optional)')}}</h5>
    <div class="form-group">

        @include('back.layouts.core.forms.checkbox-group',
        [
            'name'          => "activities[]",
            'label'         => '' ,
            'class'         => '' ,
            'required'      => false,
            'attr'          => '',
            'value'         => null,
            'placeholder'   => __('I don\'t want to have any activities' ),
            'data'          => $quotation->properties['misc_options'],
        ])
    </div>
    @endif
</div>