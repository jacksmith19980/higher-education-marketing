<form  method="POST" action="{{route('school.parent.child.create' , $school)}}" class="validation-wizard wizard-circle">
    @csrf
    
    <input type="hidden" name="booking" class="ajax-form-field" value="{{$booking}}" />
   
    @if (count($childrenList) > 1 )

        <div class="row">
            <div class="col-md-12">
                @include('back.layouts.core.forms.select',
                [
                    'name'          => 'child',
                    'label'         => 'Select Child' ,
                    'placeholder'   => 'Select or add a child',
                    'class'         => 'ajax-form-field' ,
                    'required'      => false,
                    'attr'          => 'onchange=app.addNewChild(this) data-wrapper=newChildWrapper',
                    'data'          => $childrenList,
                    'value'         => '9999999999'
                ])
            </div>
        </div>

    @endif
    
    <div class="newChildWrapper row {{ (count($childrenList) > 1 ) ? 'hidden' : '' }}">

        <div class="col-md-6">
            @include('back.layouts.core.forms.text-input',
            [
                'name'      => 'first_name',
                'label'     => 'Child\'s First Name' ,
                'class'     =>'ajax-form-field' ,
                'required'  => true,
                'attr'      => (count($childrenList) > 1 ) ? 'disabled' : '',
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
                'attr'      => (count($childrenList) > 1 ) ? 'disabled' : '',
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
                    'attr'      => (count($childrenList) > 1 ) ? 'disabled' : '',
                    'data'      => $applicationList,
                    'value'     => ''
                    ])
            </div>
        @else
            <input type="hidden" {{ (count($childrenList) > 1 ) ? 'disabled' : '' }} class='ajax-form-field' value="{{$applicationList}}" name="application">
        @endif
    
</div> <!-- row -->

</form>
