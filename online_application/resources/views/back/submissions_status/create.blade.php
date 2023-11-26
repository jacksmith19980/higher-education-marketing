@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Add')}} {{__('Application Status')}}</h4>
                    <hr>
                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                    <form method="POST" action="{{ route('applicationStatus.store') }}" class="validation-wizard wizard-circle m-t-40"
                          aria-label="{{ __('Create Application Status') }}" data-add-button="{{__('Add Application Status')}}"  enctype="multipart/form-data">
                        @csrf
                        <!-- Step 1 -->
                        <h6>{{__('Application Status')}}</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'title',
                                        'label'     => 'Value',
                                        'class'     => '',
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>

                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                        'name'      => 'label',
                                        'label'     => 'Label',
                                        'class'     => '',
                                        'required'  => false,
                                        'attr'      => '',
                                        'value'     => ''
                                    ])
                                </div>
                            </div> <!-- row -->
                        </section>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
