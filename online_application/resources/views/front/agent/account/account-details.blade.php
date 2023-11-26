<h4 class="card-title">{{__('Account Details')}}</h4>

@if (session('success'))
    <div class="alert alert-success">{{session('success')}}</div>
@endif

<form method="POST" action="{{ route('school.agent.update' , $school) }}" class="m-t-10" validate>
@csrf
<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'first_name',
            'label'     => 'First Name' ,
            'class'     => '' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $agent->first_name,
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'last_name',
            'label'     => 'Last Name' ,
            'class'     => '' ,
            'required'  => false,
            'attr'      => '',
            'value'     => $agent->last_name,
        ])
    </div>

    <div class="col-md-6">
    
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'email',
            'label'     => 'Email Address',
            'required'  => true,
            'class'     => '' ,
            'attr'      => '',
            'value'     => $agent->email,
        ])
    
    </div>

    <div class="col-md-6">
        
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'phone',
            'label'     => 'Phone Number' ,
            'class'     => '' ,
            'required'  => false,
            'attr'      => '',
            'value'     => $agent->phone,
        ])
    </div>

    <div class="col-md-6">
        
        @include('back.layouts.core.forms.password',
        [
            'name'          => 'password',
            'label'         => 'Password' ,
            'class'         => '' ,
            'placeholder'   => 'Leave empty for no change',
            'required'      => false,
            'attr'          => '',
            'value'         => '',
        ])
    </div>

    <div class="col-md-6">
        
        @include('back.layouts.core.forms.password',
        [
            'name'          => 'password_confirmation',
            'label'         => 'Confirm Password' ,
            'class'         => '' ,
            'placeholder'   => 'Leave empty for no change',
            'required'      => false,
            'attr'          => '',
            'value'         => '',
        ])
    </div>

</div> <!-- row -->
 <input type="submit" class="btn btn-success float-right" value="Apply changes">
</form>
