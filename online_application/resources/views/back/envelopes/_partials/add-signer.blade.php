<div class="row">

    <div class="col-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'role',
        'label' => "Signer's Role" ,
        'class' => 'ajax-form-field' ,
        'required' => true,
        'attr' => 'onchange=app.signerRoleSelected(this)',
        'value' => '',
        'data' => [
        'School'    => 'School',
        'Admission' => 'Admission',
        'Student'   => 'Student',
        'Parent'    => 'Parent',
        'Agent'     => 'Agent',
        ]
        ])
    </div>

    <div class="col-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'order',
        'label' => "Signer's order",
        'class' => 'ajax-form-field' ,
        'required' => true,
        'attr' => '',
        'value' => '',
        'data' => [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        ],
        ])
    </div>


    <div class="col-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'first_name',
        'label' => "Signer's First Name" ,
        'class' => 'ajax-form-field signer_data' ,
        'required' => true,
        'attr' => '',
        'value' => ''
        ])
    </div>
    <div class="col-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'last_name',
        'label' => "Signer's Last Name" ,
        'class' => 'ajax-form-field signer_data' ,
        'required' => true,
        'attr' => '',
        'value' => ''
        ])
    </div>
    <div class="col-6">
        @include('back.layouts.core.forms.text-input',
        [
        'name' => 'email',
        'label' => "Signer's Email" ,
        'class' => 'ajax-form-field signer_data' ,
        'required' => true,
        'attr' => '',
        'value' => ''
        ])
    </div>

</div>
