@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Edit Application Status')}} - {{$applicationStatus->title}}</h4>
                    <hr>
                   
                    <form method="POST" action="{{ route('applicationStatus.update', $applicationStatus) }}" class="validation-wizard wizard-circle m-t-40"
                          aria-label="{{ __('Update Application Status') }}" data-add-button="{{__('Update Application Status')}}"  enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Step 1 -->
                        <h6>{{__('Application Status Information')}}</h6>
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
                                        'value'     => $applicationStatus->title
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
                                        'value'     => $applicationStatus->label
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
