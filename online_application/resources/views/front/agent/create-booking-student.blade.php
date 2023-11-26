<form  method="POST" action="{{route('school.agent.booking.student.store' , $school)}}" class="" validate>
    @csrf

    <input type="hidden" name="booking" class="ajax-form-field" value="{{$booking}}" />

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                @include('back.layouts.core.forms.text-input', [
                    'name'      => 'first_name',
                    'label'     => 'Student\'s First Name' ,
                    'class'     =>'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => ''
                ])
            </div>

            <div class="col-md-6">
                @include('back.layouts.core.forms.text-input', [
                    'name'      => 'last_name',
                    'label'     => 'Student\'s Last Name' ,
                    'class'     =>'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => ''
                ])
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">
                @include('back.layouts.core.forms.text-input', [
                    'name'      => 'email',
                    'label'     => 'Student\'s Email' ,
                    'class'     =>'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'value'     => ''
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-md-6" style="padding-top:33px;">
                @include('back.layouts.core.forms.checkbox', [
                    'name'          => 'send_email',
                    'label'         => false,
                    'class'         => 'ajax-form-field mt-5' ,
                    'required'      => false,
                    'attr'          => '',
                    'helper_text'   => 'Send login details to the student',
                    'value'         => 1,
                    'default'       => 1
                ])
            </div>
        </div>
    </div>
</form>
