<form method="POST" action="{{ route('admissions.store') }}" enctype="multipart/form-data">

    @csrf
    <div class="row">

    
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'name',
            'label' => 'Name' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'email',
            'label' => 'Email' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  ''
        ])
    </div>
    
    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'phone',
            'label' => 'Phone' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  '',
            'helper' =>  'Including the country code'
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name' => 'timezone',
            'label' => 'Timezone' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' => "en",
            'data' => SchoolHelper::timeZone()
        ])
    </div>
    
    <div class="col-md-12 mt-3">
        <h5>Availability</h5>
    </div>
    
    @for ($i = 1; $i < 6; $i++)

    <div class="col-md-6">
        @include('back.layouts.core.forms.select', [
            'name' => 'availability['.$i.'][day]',
            'label' => null ,
            'class' => 'ajax-form-field',
            'required' => true,
            'placeholder' => 'Weekdays',
            'attr' => '',
            'value' => '',
            'data' => SchoolHelper::days()
        ])
    </div>


    <div class="col-md-3">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'availability['.$i.'][start]',
            'label' => false ,
            'placeholder' => 'Start Time (24h)' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  '',
        ])
    </div>
    <div class="col-md-3">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'availability['.$i.'][end]',
            'label' => false ,
            'placeholder' => 'End Time (24h)' ,
            'class' => 'ajax-form-field',
            'required' => true,
            'attr' => '',
            'value' =>  '',
        ])
    </div>


    @endfor
    



    
</div>
</form>