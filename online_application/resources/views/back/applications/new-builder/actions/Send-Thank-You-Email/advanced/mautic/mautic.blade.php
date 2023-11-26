<div class="row">
    
    <div class="col-md-6">
        @include('back.layouts.core.forms.checkbox',
        [
            'name'          => 'properties[email_from_mautic]',
            'label'         => 'Send email from Mautic',
            'class'         => 'ajax-form-field' ,
            'required'      => false,
            'attr'          => 'onchange=app.loadMauticEmails(this)',
            'helper_text'   => 'Yes',
            'value'         => isset($action->properties['email_from_mautic']) ? $action->properties['email_from_mautic'] : 0,
            'default'       => 1
        ])
    </div>

    <div class="col-md-12" id="MauticEmailsList">
        
    </div>
       
</div><!-- row -->