<div class="log-form-toggle signupForm active">
    <form action="{{ route('quotations.booking.email' , ['school'=> $school , 'quotation' => $quotation]) }}" method="POST"
          name="signupForm">
        @csrf

        <input type="hidden" name="booking" value="{{$booking->id}}">

        @if($account_hide)
            <input type="hidden" name="account" value="{{array_filter(array_keys($accounts_booking))[0]}}">
        @else
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    @include('back.layouts.core.forms.select', [
                        'name' => 'account',
                        'label' => '' ,
                        'class' => 'form-control' ,
                        'required' => true,
                        'attr' => 'onchange=app.accountTypeChange(this)',
                        'data'  => $accounts_booking,
                        'value' => null
                    ])
                </div>
            </div>
    @endif
    <!-- Agency data Begin -->
        <div class="row agency" style="display: none;">
            <div class="col-sm-12 col-md-12 col-lg-6">
                @include('back.layouts.core.forms.text-input', [
                    'name' => 'agency_name',
                    'label'       => '',
                    'placeholder' => 'Agency Name*',
                    'class' => '' ,
                    'required' => false,
                    'attr' => '',
                    'value' => '',
                    'data' => '',
                ])
            </div>

            <div class="col-sm-12 col-md-12 col-lg-6">
                @include('back.layouts.core.forms.email-input', [
                    'name' => 'agency_email',
                    'label' => '',
                    'placeholder' => 'Agency Email*',
                    'class' => '' ,
                    'required' => false,
                    'attr' => '',
                    'value' => '',
                    'data' => '',
                ])
            </div>
        </div>
        <!-- Agency data End -->

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
                @include('back.layouts.core.forms.text-input',
                [
                'name' => 'first_name',
                'label'       => '',
                'placeholder' => 'First Name*',
                'class' => '' ,
                'required' => true,
                'attr' => 'readonly',
                'value' => $student_information['first_name'],
                'data' => '',
                ])
            </div>

            <div class="col-sm-12 col-md-12 col-lg-6">
                @include('back.layouts.core.forms.text-input',
                [
                    'name' => 'last_name',
                    'label' => '',
                    'placeholder' => 'Last Name*',
                    'class' => '' ,
                    'required' => true,
                    'attr' => 'readonly',
                    'value' => $student_information['last_name'],
                    'data' => '',
                ])
            </div>

            <div class="col-sm-12 col-md-12 col-lg-12">
                @include('back.layouts.core.forms.email-input',
                [
                    'name' => 'email',
                    'label' => '',
                    'placeholder' => 'Email*',
                    'class' => '' ,
                    'required' => true,
                    'attr' => 'readonly',
                    'value' => $student_information['email'],
                    'data' => '',
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
                @include('back.layouts.core.forms.password', [
                    'name' => 'password',
                    'label' => '',
                    'placeholder' => 'Password*',
                    'class' => '' ,
                    'required' => true,
                    'attr' => '',
                    'value' => '',
                    'data' => '',
                    ])
            </div>

            <div class="col-sm-12 col-md-12 col-lg-6">
                @include('back.layouts.core.forms.password', [
                    'name' => 'password_confirmation',
                    'label' => '',
                    'placeholder' => 'Confirm Password*',
                    'class' => '' ,
                    'required' => true,
                    'attr' => '',
                    'value' => '',
                    'data' => '',
                ])
            </div>

            @if (isset($settings['auth']['parent_login']) && $settings['auth']['parent_login'] =='Yes')
                <input type="hidden" name="role" value="parent"/>
            @else
                <input type="hidden" name="role" value="student"/>
            @endif

            <div class="col-md-12">
                @include('back.layouts.core.forms.checkbox', [
                    'name' => 'consent',
                    'label' => false ,
                    'class' => '' ,
                    'required' => false,
                    'attr' => '',
                    'helper_text' => 'I would like to receive information about dates, details and offers for '.$school->name,
                    'value' => 0,
                    'default' => 1,
                    ])
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn is-flat btn-accent-1 my-1 float-right ml-3">
                    {{__('CONTINUE BOOKING')}}
                    <i class="fas fa-check ml-2"></i>
                </button>
                <a href="{{route('quotations.show', ['school'=> $school , 'quotation' => $quotation])}}" class="btn is-flat btn-accent-1 my-1 float-right">
                    {{__('Change quotation')}}
                    <i class="fas fa-check ml-2"></i>
                </a>
            </div>
        </div>
    </form>
</div>