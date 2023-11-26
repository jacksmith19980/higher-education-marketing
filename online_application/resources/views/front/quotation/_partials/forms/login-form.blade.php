
<form action="{{ route('quotation.login' , ['school'=> $school , 'quotation' => $quotation]) }}" method="POST" class="m-b-30">

    @csrf

    <h4 class="m-b-20">{{__('Second Step : Login to  your account')}} , <a href="javascript:void(0)" onclick="app.bookNow(this)" data-quotation-id="{{$quotation}}" data-invoice-id="{{$booking}}">{{__('or create New Account')}}</a></h4>


    <div class="row">

        <input type="hidden" value="{{$quotation}}" name="quotaionId">

        <input type="hidden" name="booking" value="{{$booking}}">


        <div class="col-md-6">

            @include('back.layouts.core.forms.email-input',

            [

                'name'      => 'email',

                'label'     => 'Email' ,

                'class'     => '' ,

                'required'  => true,

                'attr'      => '',

                'value'     => '',

                'data'      => '',

            ])

        </div>



    <div class="col-md-6">

            @include('back.layouts.core.forms.password',

            [

                'name'      => 'password',

                'label'     => 'Password' ,

                'class'     => '' ,

                'required'  => true,

                'attr'      => '',

                'value'     => '',

                'data'      => '',

            ])

        </div>

        <div class="col-md-12 clearfix">
            <input type="submit" value="{{__('Compelete Booking')}}" class="btn btn-lg btn-primary float-right">
        </div>

    </div>

</form>