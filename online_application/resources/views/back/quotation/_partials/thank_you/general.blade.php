<div class="col-md-12">
     @include('back.layouts.core.forms.text-input',
    [
    'name' => 'properties[eqnuiry_thank_you_page]',
    'label' => 'Enquiry Thank You Page' ,
    'class' =>'' ,
    'required' => false,
    'attr' => '',
    'value' => (isset($quotation->properties['eqnuiry_thank_you_page'])) ? $quotation->properties['eqnuiry_thank_you_page'] : '',
    ])
</div>
<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
        [
    'name'          => 'properties[enable_thank_you_page]',
    'label'         => 'Thank You page' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Redirect users to Thank You page after booking',
    'value'         =>  (isset($quotation->properties['enable_thank_you_page'])) ? $quotation->properties['enable_thank_you_page'] : 0,
    'default'       => 1,
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
        [
    'name'          => 'properties[show_pay_now]',
    'label'         => 'Pay Now' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Show Pay Now button in the thank you page ',
    'value'         =>  (isset($quotation->properties['show_pay_now'])) ? $quotation->properties['show_pay_now'] : 0,
    'default'       => 1,
    ])
</div>

<div class="col-md-6">
    @include('back.layouts.core.forms.checkbox',
        [
    'name'          => 'properties[show_booking_details]',
    'label'         => 'Booking Detail' ,
    'class'         => '' ,
    'required'      => false,
    'attr'          => '',
    'helper_text'   => 'Show booking details in the thank you page ',
    'value'         =>  (isset($quotation->properties['show_booking_details'])) ? $quotation->properties['show_booking_details'] : 0,
    'default'       => 1,
    ])
</div>


<div class="col-md-6">
    @include('back.layouts.core.forms.text-input',
    [
        'name'      => 'properties[banner_title]',
        'label'     => 'Banner Title' ,
        'class'     =>'' ,
        'required'  => false,
        'attr'      => '',
        'value'     => (isset($quotation->properties['banner_title'])) ? $quotation->properties['banner_title'] : '',
    ])
</div>

<div class="col-md-12">
        <div class="d-flex">
            @if (isset($quotation->properties['header_image']))


                    <img class="m-r-15" src="{{ Storage::disk('s3')->temporaryUrl($quotation->properties['header_image']['path'], \Carbon\Carbon::now()->addMinutes(5)) }}" alt="Generic placeholder image" style="max-width:150px;">

                    <input type="hidden" value="{{$quotation->properties['header_image']['path']}}" name="properties[header_image][path]" />


                    <input type="hidden" value="{{$quotation->properties['header_image']['name']}}" name="properties[header_image][name]" />

            @endif
                <div class="form-group">
                    @include('back.layouts.core.forms.file-input',
                        [
                            'name'          => 'header_image',
                            'label'         => 'Banner Image' ,
                            'class'         => '',
                            'required'      => false,
                            'attr'          => '',
                            'value'         => '',
                        ])
                </div>
        </div>
</div>
<div class="col-md-12 mt-4">
    @include('back.layouts.core.forms.html',
    [
        'name'      => 'properties[thank_you_copy]',
        'label'     => '<strong>Thank You Text</strong>' ,
        'class'     =>'' ,
        'required'  => false,
        'attr'      => '',
        'value'     =>  (isset($quotation->properties['thank_you_copy'])) ? $quotation->properties['thank_you_copy'] : '',
        'data'      => ''
    ])
</div>
