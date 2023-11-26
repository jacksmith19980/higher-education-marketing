<div class="row">
    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'properties[destination]',
            'label'     => 'Destination (Unique Code)' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => (isset( $payment->properties['destination'] )) ? $payment->properties['destination'] : ''
        ])
    </div>
    @include('back.applications.new-builder.payments._partials.general-settings')

</div> <!-- row -->
