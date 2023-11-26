<form action="{{route('send.quotation.email' , $school)}}" method="POST" class="m-b-30">

    @csrf

    <div class="row">



        <input type="hidden" value="{{$quotation->id}}" name="quotaionId">

        <input type="hidden" name="booking" value="{{$booking}}">

        

        <div class="col-md-6">

            @include('back.layouts.core.forms.text-input',

            [

                'name'      => 'first_name',

                'label'     => 'First Name' ,

                'class'     => '' ,

                'required'  => true,

                'attr'      => '',

                'value'     => '',

                'data'      => '',

            ])

        </div>



    <div class="col-md-6">

            @include('back.layouts.core.forms.text-input',

            [

                'name'      => 'last_name',

                'label'     => 'Last Name' ,

                'class'     => '' ,

                'required'  => true,

                'attr'      => '',

                'value'     => '',

                'data'      => '',

            ])

        </div>



        <div class="col-md-12">

            @include('back.layouts.core.forms.text-input',

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



        <div class="col-md-12 clearfix">

            <input type="submit" value="{{__('SEND NOW')}}" class="btn btn-lg btn-success float-right">

        </div>

    </div>

</form>