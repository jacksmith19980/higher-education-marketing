<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.checkbox',
        [
            'name'      	=> 'properties[smart]',
            'label'     	=> 'Smart Filed' ,
            'class'     	=> 'ajax-form-field is_smart_field' ,
            'required'  	=> false,
            'attr'      	=> 'onchange=app.smartFieldSwitch(this) data-application='.optional($application)->id.' ',
            'helper_text' 	=> 'This is a Smart Field',
            'value'     	=> isset(optional($field)->properties['smart']) && optional($field)->properties['smart'] ? 1 : 0,
            'default'		=> 1,
        ])
    </div>
</div> <!-- row -->
<div class="intelligence_rules">

    @if (isset(optional($field)->properties['smart']))

        @include('back.applications._partials.intelligence.edit_rule' , ['logic' => $field->properties['logic']])

    @endif

</div>
