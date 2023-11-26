@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Custom Field')}}</h4>
                        <hr>
                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('customfields.store') }}"
                              class="validation-wizard wizard-circle m-t-40"
                              aria-label="{{ __('Create Custom Field') }}" data-add-button="{{__('Add Custom Field')}}"
                              enctype="multipart/form-data">
                        @csrf
                        <!-- Step 1 -->
                            <h6>{{__('Customfield Information')}}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'name',
                                            'label'     => 'Name' ,
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => 'onblur=app.constructSlug(this)',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name'      => 'slug',
                                            'label'     => 'Code',
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => 'onkeyup=app.validateFieldName(this)',
                                            'value'     => ''
                                        ])
                                        @if ($errors->has('slug'))
                                            <span class="error">
                                                <strong>{{ $errors->first('slug') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div> <!-- row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'          => 'parent',
                                            'label'         => 'Parent',
                                            'class'         => '',
                                            'required'      => true,
                                            'attr'          => '',
                                            'value'         => '',
                                            'placeholder'   => 'Select Parent',
                                            'data'          => $parents
                                        ])
                                    </div>
                                </div> <!-- row -->

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.checkbox',
                                    [
                                        'name'          => 'mandatory',
                                        'label'         => '',
                                        'helper_text'   => 'Mandatory',
                                        'class'         => '',
                                        'default'       => 1,
                                        'required'      => false,
                                        'attr'          => '',
                                        'value'         => '',
                                        'placeholder'   => '',
                                        'data'          => ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.checkbox',
                                    [
                                        'name'          => 'for_forms',
                                        'label'         => '',
                                        'helper_text'   => 'Use In Froms',
                                        'class'         => '',
                                        'default'       => 1 ,
                                        'required'      => false,
                                        'attr'          => '',
                                        'value'         => '',
                                        'placeholder'   => '',
                                        'data'          => ''
                                    ])
                                </div>
                            </section>

                            <!-- Step 2 -->
                            <h6>{{__('Data')}}</h6>
                            <section>
                                @include('back.customFields._partials.data')
                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
