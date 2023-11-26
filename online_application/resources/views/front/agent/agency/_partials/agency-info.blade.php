<h6>{{__('Agency Information')}}</h6>
<section>
<div class="row">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'name',
            'label'     => 'Agency Name' ,
            'class'     => '' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $agency->name,
        ])
    </div>
    
    <div class="col-md-6">
        
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'phone',
            'label'     => 'Phone Number' ,
            'class'     => '' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $agency->phone,
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
            'value'     => $agency->email,
        ])
    
    </div>

       <div class="col-md-12">
    
        @include('back.layouts.core.forms.text-area',
        [
            'name'      => 'address',
            'label'     => 'Address',
            'required'  => true,
            'class'     => '' ,
            'attr'      => '',
            'value'     => $agency->address,
        ])
    
    </div>

    <div class="col-md-6">
    
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'postal_code',
            'label'     => 'Postal/Zip Code' ,
            'class'     =>'form-control-lg form-control' ,
            'required'  => false,
            'attr'      => '',
            'value'     => $agency->postal_code,
        ])
    </div>

    <div class="col-md-6">
    
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'city',
            'label'     => 'City' ,
            'class'     =>'form-control-lg form-control' ,
            'required'  => true,
            'attr'      => '',
            'value'     => $agency->city,
        ])
    </div>

    <div class="col-md-6">
        
        @include('back.layouts.core.forms.select',
        [
            'name'          => 'country',
            'label'         => 'Country' ,
            'required'      => true,
            'class'         => 'select2 lookup' ,
            'placeholder'   => 'Select Country',
            'data'          => FieldsHelper::getListData('countries_full'),
            'attr'          => '',
            'value'         => $agency->country,
        ])
    </div>


    <div class="col-md-12">
        @include('back.layouts.core.forms.text-area',
        [
            'name'      => 'description',
            'label'     => 'Description' ,
            'class'     =>'' ,
            'required'  => false,
            'attr'      => '',
            'value'     => $agency->description,
        ])
    </div>
</div> <!-- row -->
</section>