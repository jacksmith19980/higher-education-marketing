<form action="{{ route('quotation.booking' , ['school'=> $school , 'quotation' => $quotation]) }}" method="POST" class="m-b-30">

    @csrf



    <h4 class="m-b-20">{{__('Second Step : Create an account or ')}} <a href="#">{{'Login'}}</a> </h4>



    <p class="alert alert-info"> 

        <strong>To book,</strong> fill out the form below to create your account. 

        Then click <i>Add Child</i> to add your child’s details.

    </p>

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

       {{--  @if (isset($settings['auth']['parent_login']) && $settings['auth']['parent_login'] =='Yes')

      

        <div class="col-6">

            @include('back.layouts.core.forms.select',

            [

                'name'      => 'role',

                'label'     => "I'm a" ,

                'class'     => 'select2' ,

                'required'  => true,

                'attr'      => '',

                'value'     => 'parent',

                'data'      => [

                    'parent'  => 'Parent',

                    'student' => 'Student'

                ],

            ])

        </div> 



        @else

            <input type="hidden" name="role" value="student" />

        @endif --}}

        

        <input type="hidden" name="role" value="parent" />







        <div class="col-md-12 clearfix">

            <input type="submit" value="{{__('Complete Booking')}}" class="btn btn-lg btn-primary float-right">



            {{-- <a href="{{ route('school.home' , $school) }}">{{__('I already have an account')}}</a> --}}



        </div>

    </div>

</form>