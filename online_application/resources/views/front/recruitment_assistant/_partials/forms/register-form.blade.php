<div class="log-form-toggle signupForm active">
    <form action="{{ route('assistants.register' , ['school'=> $school , 'assistantBuilder' => $assistantBuilder]) }}" method="POST" name="signupForm">
        @csrf


        <div class="row">

            <div class="col-sm-12 text-center mb-4">
            <h4 class="d-block text-center">{{__('Apply Now!')}}</h4>
                {{__('Complete the form to start your application.')}}
            </div>


            <input type="hidden" value="{{$assistantBuilder->id}}" name="quotaionId">
            <input type="hidden" value="{{json_encode($cart['cart'])}}" name="cart">
            <input type="hidden" value="{{isset($cart['campuses']) ? $cart['campuses'][0]['title'] : ''}}" name="campus">

            <div class="col-sm-12 col-md-12 col-lg-6">
                @include('back.layouts.core.forms.text-input',
                [
                'name' => 'first_name',
                'label'       => '',
                'placeholder' => 'First Name*',
                'class' => '' ,
                'required' => true,
                'attr' => '',
                'value' => '',
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
                    'attr' => '',
                    'value' => '',
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
                    'attr' => '',
                    'value' => '',
                    'data' => '',
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
                @include('back.layouts.core.forms.password',
                    [
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
                @include('back.layouts.core.forms.password',
                    [
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

            <div class="col-md-12">
                @include('back.layouts.core.forms.checkbox',
                [
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
                <button type="submit" class="btn btn-accent-1 my-1 float-right">
                    {{__('Start Applying Now')}}
                    <i class="fas fa-check ml-2"></i>
                </button>

                <!-- <a href="javascript:void(0)" class="btn btn-outline-accent-1 my-1 float-right mr-2">
                    {{__('Receive Summary')}}
                </a> -->

            </div>

            <div class="col-md-12 text-center mt-3">
                <p class="d-block">Already have an account?
                    <a href="javascript:void(0)"><strong class="color-primary log-form-btn">Log in!</strong></a>
                </p>
            </div>

        </div>
    </form>
</div>