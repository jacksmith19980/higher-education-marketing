<form method="POST" action="{{route('submissions.store.new')}}" enctype="multipart/form-data">
<div class="row">
    <div class="col-6">
        @include('back.layouts.core.forms.text-input',
            [
            'name' => 'firstname',
            'label' => 'First Name',
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => ''
        ])
    </div>
    <div class="col-6">
        @include('back.layouts.core.forms.text-input',
            [
            'name' => 'lastname',
            'label' => 'Last Name',
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => ''
        ])
    </div>
    <div class="col-6">
        @include('back.layouts.core.forms.email-input',
            [
            'name' => 'email',
            'label' => 'Email',
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => ''
        ])
    </div>
    <div class="col-6">
        {{--  @include('back.layouts.core.forms.select',
            [
            'name' => 'campus',
            'placeholder' => '--Select Campus--',
            'label' => 'Campus',
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => '',
            'data' => $campuses,
        ])  --}}

        @include('back.layouts.core.forms.select',
        [
            'name'      => 'application',
            'label'     => 'Application' ,
            'class'     => 'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'data'      => $applicationList,
            'value'     => ''
        ])


    </div>

    <div class="col-12">
        @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'send_email',
                'label'         => false,
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Send login details to the student',
                'value'         => 0,
                'default'       => 1
            ])
    </div>

    </div>


</form>
