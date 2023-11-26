<form  method="POST" action="{{ route($route , ['invoice' => $invoice , 'student' => $student]) }}" class="validation-wizard wizard-circle" id="ajax-form">

    @csrf

        @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'invoice',
            'label'     => '' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $invoice->id
        ])

         @include('back.layouts.core.forms.hidden-input',
        [
            'name'      => 'student',
            'label'     => '' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $student->id
        ])

    <div class="row">
        <div class="col-md-12">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'subject',
                'label'     => 'Subject' ,
                'class'     =>'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'value'     => ''
            ])
        </div>
        <div class="col-md-6">
            @include('back.layouts.core.forms.checkbox',
            [
                'name'          => 'include_payment_link',
                'label'         => '' ,
                'class'         => 'ajax-form-field' ,
                'required'      => false,
                'attr'          => '',
                'helper_text'   => 'Include Payment Link',
                'value'         => '',
                'default'       => true,
            ])
        </div>
        <div class="col-md-12">
                @include('back.layouts.core.forms.html',
            [
                'name'      => 'body',
                'label'     => 'Email',
                'class'     => 'ajax-form-field',
                'required'  => true,
                'value'     => '',
                'attr'      => '',
            ])
        </div>
    </div><!-- row -->

</form>
