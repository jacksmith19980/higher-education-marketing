@extends('back.layouts.default')

@section('content')
    <div class="container">
        <input type="hidden" id="current_id" value="1">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{__('Add Report')}}</h4>
                        <hr>
                        <form method="POST" action="{{ route('reports.store') }}"
                              class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Add New') }}"
                              data-add-button="{{__('Add New') }}">
                            @csrf
                            <h6>{{__('Details')}}</h6>
                            <section>

                                <div class="row">
                                    @if (!count($reportCategories))
                                        <div class="alert alert-danger col-12">
                                            {{ __('You don\'t have any educational report category, Please add one first') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input',
                                        [
                                            'name'      => 'name',
                                            'label'     => 'Name' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => '',
                                            'value'     => ''
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'          => 'source',
                                            'label'         => 'Data Source' ,
                                            'placeholder'   => 'Select Table',
                                            'class'         => '' ,
                                            'required'      => true,
                                            'attr'          => 'id=source data-route=' . route('reports.table-columns'),
                                            'value'         => '',
                                            'data'          => $tables,
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'          => 'category',
                                            'label'         => 'Category' ,
                                            'class'         => '' ,
                                            'required'      => true,
                                            'attr'          => '',
                                            'value'         => '',
                                            'data'         => $reportCategories,
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                    @include('back.layouts.core.forms.campuses', [
                                        'class'     => '',
                                        'required'  => true,
                                        'attr'      => '',
                                        'value'     => '',
                                        'data'      => $campuses
                                    ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="description">{{ __('Description') }}<span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control mb-2" id="description" required rows="4"></textarea>
                                    </div>
                                </div>
                            </section>
                            <h6>{{__('Data')}}</h6>
                            <section>
                                <h4 class="card-title">{{__('Columns') }}</h4>
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.duallistbox',
                                    [
                                        'name'      => 'columns[]',
                                        'label'     => '' ,
                                        'class'     => 'duallistbox' ,
                                        'required'  => true,
                                        'attr'      => '',
                                        'id'        => 'table-columns',
                                        'value'     => '',
                                        'data'      => []
                                    ])
                                </div>

                                <div class="row" id="filters">
                                </div>

                                <div class="row options_group_transfer">
                                    <div class="col-md-3 offset-9 m-b-20">
                                        @include('back.classrooms._partials.add-slot-button' , [
                                            'action' => 'report.addFilter',
                                            'container' => '#filters',
                                            'text' => 'Add Filter',
                                            'dataSource' => 'source'
                                        ])
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
