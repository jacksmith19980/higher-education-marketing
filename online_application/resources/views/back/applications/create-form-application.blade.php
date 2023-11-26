@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Application')}}</h4>
                        <form method="POST" action="{{ route('applications.store') }}" class="validation-wizard wizard-circle m-t-40 needs-validation" aria-label="{{ __('Create Application') }}" data-add-button="Create Application" >

                        @csrf
                        <!-- Step 1 -->
                            @include('back.applications._partials.application-creation.form.general')

                            @include('back.applications._partials.application-creation.form.customization')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
