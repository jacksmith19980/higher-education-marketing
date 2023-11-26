<form action="{{route('assistants.email' , ['school' => $school , 'assistantBuilder' => $assistantBuilder])}}" method="POST" name="sendToEmailForm">
        @csrf
        <h3>Send summary via email</h3>

            <input type="hidden" value="{{$assistantBuilder->id}}" name="assistantBuilderId">
            <input id="cart" type="hidden" value="{{json_encode($cart)}}" name="cart">
            <input type="hidden" value="{{isset($cart['campuses']) ? $cart['campuses'][0]['title'] : null}}" name="campus">

            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-3">
                    @include('back.layouts.core.forms.select',
                    [
                        'name' => 'title',
                        'label' => 'Title' ,
                        'class' => 'form-control' ,
                        'required' => true,
                        'attr' => '',
                        'data'  => [
                            ''      => 'Select',
                            'Mr'    => 'Mr',
                            'Mrs'   => 'Mrs',
                            'Ms'    => 'Ms',
                        ],
                        'value' => null
                    ])
                </div>
                <div class="col-sm-12 col-md-6 col-lg-5">

                    @include('back.layouts.core.forms.text-input',
                    [
                        'name' => 'first_name',
                        'label' => 'First Name' ,
                        'class' => '' ,
                        'required' => true,
                        'attr' => '',
                        'value' => '',
                        'data' => '',
                    ])
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    @include('back.layouts.core.forms.text-input',
                    [
                        'name' => 'last_name',
                        'label' => 'Last Name' ,
                        'class' => '' ,
                        'required' => true,
                        'attr' => '',
                        'value' => '',
                        'data' => '',
                    ])
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12">
                    @include('back.layouts.core.forms.text-input',
                    [
                        'name' => 'phone',
                        'label' => 'Phone Number' ,
                        'class' => 'inter-calling-code form-control' ,
                        'required' => true,
                        'attr' => '',
                        'value' => '',
                        'data' => '',
                    ])
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12 mt-3">
                    @include('back.layouts.core.forms.text-input',
                    [
                    'name' => 'email',
                    'label' => 'Email' ,
                    'class' => '' ,
                    'required' => true,
                    'attr' => '',
                    'value' => '',
                    'data' => '',
                    ])
                </div>
            </div>

            <button type="submit" class="btn is-flat btn-accent-1 my-1 float-right">{{__('Send Now')}}</button>
</form>