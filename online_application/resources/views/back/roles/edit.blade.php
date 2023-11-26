@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Edit Role')}}</h4>
                        <hr>
                        <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                        <form method="POST" action="{{ route('roles.update', $role) }}" class="validation-wizard wizard-circle m-t-40"
                                aria-label="{{ __('Update Role') }}" data-add-button="{{__('Update Role')}}"  enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Step 1 -->
                            <h6>Role Information</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'name',
                                            'label'     => 'Role' ,
                                            'class'     => '',
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => isset($role->name) ? $role->name : ''
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'full_access',
                                            'label'     => 'Has full system access?' ,
                                            'attr'      => 'onchange=app.toggleFullAccessPermission(this)',
                                            'class'     => '',
                                            'required'  => true,
                                            'data'      => [
                                                'Yes' => 'Yes',
                                                'No'  => 'No',
                                            ],
                                            'value'     => (isset($role->full_access) && $role->full_access) ? 'Yes' : 'No'
                                        ])
                                    </div>

                                </div> <!-- row -->
                            </section>

                            <h6>{{__('Permissions')}}</h6>
                            <section>
                                <div id="permissions_selection" class="row {{($role->full_access) ? 'hidden' : ''}}">
                                    <div class="col-lg-4 col-xl-3">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                                            <a class="nav-link active" id="v-pills-campuses-tab" data-toggle="pill" href="#v-pills-campuses" role="tab" aria-controls="v-pills-campuses" aria-selected="true">{{__('Campuses')}}</a>

                                            <a class="nav-link" id="v-pills-courses-tab" data-toggle="pill" href="#v-pills-courses" role="tab" aria-controls="v-pills-courses" aria-selected="true">{{__('Course')}}</a>

                                            <a class="nav-link" id="v-pills-programs-tab" data-toggle="pill" href="#v-pills-programs" role="tab" aria-controls="v-pills-programs" aria-selected="false">{{__('Programs')}}</a>

                                            <a class="nav-link" id="v-pills-users-tab" data-toggle="pill" href="#v-pills-users" role="tab" aria-controls="v-pills-users" aria-selected="false">{{__('Users')}}</a>

                                            <a class="nav-link" id="v-pills-contacts-tab" data-toggle="pill" href="#v-pills-contacts" role="tab" aria-controls="v-pills-contacts" aria-selected="false">{{__('Contacts')}}</a>

                                            <a class="nav-link" id="v-pills-agents-tab" data-toggle="pill" href="#v-pills-agents" role="tab" aria-controls="v-pills-agents" aria-selected="false">{{__('Agents')}}</a>

                                            <a class="nav-link" id="v-pills-applications-tab" data-toggle="pill" href="#v-pills-applications" role="tab" aria-controls="v-pills-applications" aria-selected="false">{{__('Applications')}}</a>

                                            <a class="nav-link" id="v-pills-applications-tab" data-toggle="pill" href="#v-pills-submissions" role="tab" aria-controls="v-pills-submissions" aria-selected="false">{{__('Submissions')}}</a>

                                            <a class="nav-link" id="v-pills-submission-statuses-tab" data-toggle="pill" href="#v-pills-submission-statuses" role="tab" aria-controls="v-pills-submission-statuses" aria-selected="false">{{__('Submission Statuses')}}</a>

                                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{__('Settings')}}</a>

                                            <a class="nav-link" id="v-pills-fields-tab" data-toggle="pill" href="#v-pills-fields" role="tab" aria-controls="v-pills-fields" aria-selected="false">{{__('Fields')}}</a>

                                            <a class="nav-link" id="v-pills-e-signatures-tab" data-toggle="pill" href="#v-pills-e-signatures" role="tab" aria-controls="v-pills-e-signatures" aria-selected="false">{{__('E-signatures')}}</a>

                                        </div>
                                    </div>

                                    <div class="col-lg-8 col-xl-9">
                                        <div class="tab-content" id="v-pills-tabContent">

                                            <div class="tab-pane fade show active" id="v-pills-campuses" role="tabpanel" aria-labelledby="v-pills-school-tab">@include('back.permissions.campuses')</div>

                                            <div class="tab-pane fade show" id="v-pills-courses" role="tabpanel" aria-labelledby="v-pills-branding-tab">@include('back.permissions.courses')</div>

                                            <div class="tab-pane fade show" id="v-pills-programs" role="tabpanel" aria-labelledby="v-pills-branding-tab">@include('back.permissions.programs')</div>

                                            <div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab">@include('back.permissions.users')</div>

                                            <div class="tab-pane fade" id="v-pills-contacts" role="tabpanel" aria-labelledby="v-pills-contacts-tab">@include('back.permissions.contacts')</div>


                                            <div class="tab-pane fade" id="v-pills-agents" role="tabpanel" aria-labelledby="v-pills-admissions-tab">@include('back.permissions.agents')</div>

                                            <div class="tab-pane fade" id="v-pills-applications" role="tabpanel" aria-labelledby="v-pills-plugins-tab">@include('back.permissions.applications')</div>

                                            <div class="tab-pane fade" id="v-pills-submissions" role="tabpanel" aria-labelledby="v-pills-plugins-tab">@include('back.permissions.submissions')</div>

                                            <div class="tab-pane fade" id="v-pills-submission-statuses" role="tabpanel" aria-labelledby="v-pills-submission-statuses-tab">@include('back.permissions.stages')</div>

                                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-plugins-tab">@include('back.permissions.settings')</div>

                                            <div class="tab-pane fade" id="v-pills-fields" role="tabpanel" aria-labelledby="v-pills-plugins-tab">@include('back.permissions.fields')</div>

                                            <div class="tab-pane fade" id="v-pills-e-signatures" role="tabpanel" aria-labelledby="v-pills-e-signatures-tab">@include('back.permissions.envelopes')</div>

                                        </div>
                                    </div>
                                </div>
                                <div id="full_permission" class="{{(!$role->full_access) ? 'hidden' : ''}}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-warning">
                                                <h4>{{__('Full access granted')}}</h4>
                                                <p>{{__("The role is set to have full access granted. To adjust individual permissions, disable full access on the Role Information tab.")}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <h6>{{__('Users')}}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Select Students</h4>
                                                <div class="col-md-12">
                                                    @include('back.layouts.core.forms.duallistbox',
                                                    [
                                                        'name'      => 'users[]',
                                                        'label'     => '' ,
                                                        'class'     => 'duallistbox' ,
                                                        'required'  => false,
                                                        'attr'      => '',
                                                        'value'     => \App\Helpers\School\ModelHelpers::convertFirstNameLastnameInNameAssocWithId($role->users),
                                                        'data'      => $users
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
