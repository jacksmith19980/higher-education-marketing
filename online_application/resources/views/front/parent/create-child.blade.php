<form  method="POST" action="{{route('school.parent.child.create' , $school)}}" class="validation-wizard wizard-circle">
    @csrf
    
<div class="row">

     
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'first_name',
            'label'     => 'Child\'s First Name' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'last_name',
            'label'     => 'Child\'s Last Name' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => ''
        ])
    </div>
    
    @if (is_array($applicationList))
        
        <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                'name'      => 'application',
                'label'     => 'Application' ,
                'class'     => 'ajax-form-field' ,
                'required'  => false,
                'attr'      => '',
                'data'      => $applicationList,
                'value'     => ''
                ])
        </div>
    @else
        <input type="hidden" class='ajax-form-field' value="{{$applicationList}}" name="application">
    @endif
    
</div> <!-- row -->

</form>
