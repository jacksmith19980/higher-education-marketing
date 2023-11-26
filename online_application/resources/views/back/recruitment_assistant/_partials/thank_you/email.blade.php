<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
    [
    'name' => 'properties[thank_you_email_from_mautic]',
    'label' => 'Send email from Mautic',
    'class' => '' ,
    'required' => false,
    'attr' => 'onchange=app.loadMauticEmails(this)',
    'helper_text' => 'Yes',
    'value' => isset($assistantBuilder->properties['thank_you_email_from_mautic']) ?
    $assistantBuilder->properties['thank_you_email_from_mautic'] : 0,
    'default' => 1
    ])
</div>
<div class="col-md-6" id="MauticEmailsList"></div>



<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[thank_you_sender_name]',
    'label' => 'Sender name' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['thank_you_sender_name'])) ?
    $assistantBuilder->properties['thank_you_sender_name'] : '',
    ])
</div>
<div cslass="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[thank_you_sender_email]',
    'label' => 'Sender email' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['thank_you_sender_email'])) ?
    $assistantBuilder->properties['thank_you_sender_email'] : '',
    ])
</div>
<div class="col-md-12">
    @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[thank_you_subject]',
    'label' => 'Subject' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['thank_you_subject'])) ? $assistantBuilder->properties['thank_you_subject'] : '',
    ])
</div>

<div class="col-md-12">
    @include('back.layouts.core.forms.html',
    [
    'name' => 'properties[thank_you_email]',
    'label' => 'Thank You Email' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($assistantBuilder->properties['thank_you_email'])) ? $assistantBuilder->properties['thank_you_email'] : '',
    'data' => '',
    'helper' => '%FIRST_NAME% = First Name, %LAST_NAME% = Last Name, %EMAIL% = email, %BOOKING_DETAILS% = Quotation
    Details, %BOOKING_BUTTON% = Booking Button, %PRICE% = Total Price'
    ])
</div>