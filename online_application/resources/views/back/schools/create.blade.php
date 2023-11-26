@extends('back.layouts.default')


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">Add School</h4>
                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('schools.store') }}"
                            class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Creat School') }}"
                            data-add-button="Add School" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1 -->
                            <h6>School Information</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-input',
                                            [
                                                'name'      => 'name',
                                                'label'     => 'School Name' ,
                                                'class'     =>'' ,
                                                'required'  => true,
                                                'attr'      => '',
                                                'value'     => ''
                                        ])
                                    </div>

                                    @if(isset($plan))
                                        <div class="col-md-3">
                                            @include('back.layouts.core.forms.text-input',
                                                [
                                                    'name'      => 'plan',
                                                    'label'     => 'School Plan' ,
                                                    'class'     => '',
                                                    'required'  => true,
                                                    'attr'      => 'disabled',
                                                    'value'     => $plan->title
                                            ])
                                        </div>
                                    @endif

                                    <div class="col-md-12">
                                        @include('back.layouts.core.forms.text-area',
                                            [
                                                'name'      => 'description',
                                                'label'     => 'Description' ,
                                                'class'     =>'' ,
                                                'required'  => false,
                                                'attr'      => '',
                                                'value'     => ''
                                        ])
                                    </div>
                                </div> <!-- row -->
                            </section>

                            <!-- Step 2 -->
                            <h6>School Settings</h6>
                            <section>
                                @include('back.schools._partials.settings')
                            </section>

                            <!-- Step 3 -->
                            <h6>Invite Users</h6>
                            <section>
                                @include('back.schools._partials.invite-users')
                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
