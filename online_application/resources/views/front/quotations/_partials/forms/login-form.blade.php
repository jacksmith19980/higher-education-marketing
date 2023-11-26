<div class="log-form-toggle signupForm">

    <form action="{{ route('quotations.login' , ['school'=> $school , 'quotation' => $quotation]) }}" method="POST"
          name="loginForm">
        @csrf
        <input type="hidden" value="{{$quotation->id}}" name="quotaionId">
        <input id="cart" type="hidden" value="{{json_encode($cart)}}" name="cart">
        <h3>
            {{__('Login to your account or ')}}
            <span class="d-block color-primary log-form-btn">{{__('Create a new account')}}</span>
        </h3>

        <div class="row">

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

            @if($account_hide)
                <input type="hidden" name="account" value="{{array_filter(array_keys($accounts_booking))[0]}}">
            @else
                <div class="col-sm-12 col-md-12 col-lg-12">
                    @include('back.layouts.core.forms.select',
                    [
                        'name' => 'account',
                        'label' => '' ,
                        'class' => 'form-control' ,
                        'required' => true,
                        'attr' => '',
                        'data'  => $accounts_booking,
                        'value' => null
                    ])
                </div>
            @endif

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
            <div class="col-sm-12 col-md-12 col-lg-12">
                <button type="submit" class="btn is-flat btn-accent-1 my-1 float-right">{{__('CONTINUE BOOKING')}} <i
                            class="fas fa-check ml-2"></i></button>
            </div>
        </div>

    </form>
</div>
