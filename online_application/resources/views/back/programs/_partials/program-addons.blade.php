<div class="row">
    <div class="col-md-2 offset-10 m-b-30">
        @include('back.layouts.core.helpers.add-elements-button' , [
            'text'          => 'Add Addons',
            'action'        => 'course.addAddons',
            'container'     => '#addons_wrapper'
        ])
    </div>
</div>

<div class="row" id="addons_wrapper"></div>

<div class="row">
    @if (isset($program->properties['addons']['addon_options_category']) && isset($program->properties['addons']['addon_options']) && !empty($program->properties['addons']['addon_options_price']))
        @foreach ($program->properties['addons']['addon_options'] as $key=>$option )

            @include('back.courses._partials.addons.addon-row' , [
                'option'    => $option,
                'category'  => $program->properties['addons']['addon_options_category'][$key],
                'price'     => $program->properties['addons']['addon_options_price'][$key],
            ])
    
        @endforeach    
    @endif
</div>


