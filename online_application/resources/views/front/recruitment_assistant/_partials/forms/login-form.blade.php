<div class="log-form-toggle signupForm">
    <form action="{{ route('assistants.login' , ['school'=> $school , 'assistantBuilder' => $assistantBuilder]) }}"
          method="POST"
          name="loginForm">
        @csrf
        <input type="hidden" value="{{$assistantBuilder->id}}" name="assistantBuilderId">
                <input id="cart" type="hidden" value="{{json_encode($cart)}}" name="cart">
        

        <div class="row">
            
            <div class="col-sm-12 text-center mb-4">
                <h4 class="d-block text-center">{{__('Apply Now!')}}</h4>
                {{__('Log in to your account to complete your application.')}}
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

            <div class="col-sm-12 col-md-12 col-lg-12">
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
            
            <div class="col-sm-12 ">
                <button type="submit" class="btn  btn-accent-1 my-1 float-right">{{__('Start Applying Now')}} <i
                            class="fas fa-check ml-2"></i></button>
                
               
            </div>

            <div class="col-md-12 text-center mt-3">
                <p class="d-block">Don't have an account? 
                    <a href="javascript:void(0)"><strong class="color-primary log-form-btn toggle-form">Create One!</strong></a></p>
            </div>
        </div>

    </form>
</div>