@extends('back.layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{ __('Add List') }}</h4>
                        <hr>
                        <form method="POST" action="{{ route('lists.store') }}" class="validation-wizard wizard-circle m-t-40"
                            aria-label="{{ __('Add New') }}" data-add-button="{{ __('Add New') }}">
                            @csrf
                            <h6>{{ __('Information') }}</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'name',
                                            'label' => 'Name',
                                            'class' => '',
                                            'required' => true,
                                            'attr' => '',
                                            'value' => '',
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.switch', [
                                            'name' => 'active',
                                            'label' => __('Active'),
                                            'class' => 'switch ajax-form-field',
                                            'required' => true,
                                            'attr' => 'data-on-text=Yes data-off-text=No',
                                            'helper_text' => '',
                                            'value' => true,
                                            'default' => true,
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="description">{{ __('Description') }}<span
                                                class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control mb-2" id="description" required rows="4"></textarea>
                                    </div>
                                </div>
                            </section>

                            <h6>{{ __('Filters') }}</h6>
                            <section>

                                <div class="row">

                                    <div class="col-md-2">
                                        {{ __('Add Filters') }}:
                                    </div>

                                    <div class="col-md-3">
                                        @include('back.layouts.core.forms.select', [
                                            'name' => 'field_0',
                                            'class' => '',
                                            'placeholder' => 'Choose Field',
                                            'required' => true,
                                            'attr' => 'onchange=getFieldDetails(0)',
                                            'value' => '',
                                            'data' => $columns,
                                        ])
                                    </div>

                                    <div class="col-md-2">
                                        @include('back.layouts.core.forms.select', [
                                            'name' => 'condition_0',
                                            'class' => '',
                                            'required' => true,
                                            'attr' => '',
                                            'value' => '',
                                            'data' => [
                                                'equals' => 'Equals',
                                            ],
                                        ])
                                    </div>

                                    <div class="col-md-5" id="value_container_0">
                                        @include('back.layouts.core.forms.select', [
                                            'name' => 'value_0',
                                            'class' => '',
                                            'required' => true,
                                            'attr' => '',
                                            'value' => '',
                                            'data' => [],
                                        ])
                                    </div>
                                </div>


                                <div class="" id="lists_wrapper">
                                </div>
                                <input type="hidden" id="current_id" name="current_id" value="1">
                                <div class="row">
                                    <div class="col-md-2 offset-10 m-b-30">
                                        @include('back.layouts.core.helpers.add-elements-button', [
                                            'text' => 'Add Condition',
                                            'action' => 'liste.getCondition',
                                            'container' => '#lists_wrapper',
                                            'source' => 'data_source',
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

@section('scripts')
    <script>
        var getFieldDetails = (id) => {
            $('#condition_' + id).empty()
            field = $('#field_' + id).val();

            var data = {
                action: 'liste.getFieldData',
                payload: {
                    field
                }
            };

            app.appAjax('POST', data, app.ajaxRoute).then(function(data) {

                if (data.response == 'success' && data.status == 200) {
                    for (const item in data.extra.conditions) {
                        $('#condition_' + id).append(
                            `<option value="${item}">${data.extra.conditions[item]}</option>`
                        )
                    }

                    switch (data.extra.type) {
                        case 'string':
                            $('#value_container_' + id)
                                .html(
                                    `<input id="value_${id}" type="text" class="form-control form-control-lg" name="value_${id}" required aria-invalid="false">`
                                )
                            break;
                        case 'integer':
                            $('#value_container_' + id)
                                .html(
                                    `<input id="value_${id}" type="number" class="form-control form-control-lg" name="value_${id}" required aria-invalid="false">`
                                )
                            break;
                        case 'date':
                            $('#value_container_' + id)
                                .html(
                                    `<input id="value_${id}" type="text" class="datepicker-autoclose form-control ajax-form-field form-control-lg" name="value_${id}" value="" required="" onchange="" autocomplete="off" >`
                                )
                            break;
                        case 'boolean':
                            $('#value_container_' + id)
                                .html(
                                    `<input class="switch" id="value_${id}" name="value_${id}" type="checkbox">`
                                )
                            break;
                        case 'list':
                        case 'enum':
                            $('#value_container_' + id)
                                .html(
                                    `<select class="form-control custom-select select2 form-control-lg" name="value_${id}" id="value_${id}"></select>`
                                )
                            $('#value_' + id).empty()
                            for (const item in data.extra.data) {
                                console.log({
                                    item
                                })
                                $('#value_' + id).append(
                                    `<option value="${item}">${data.extra.data[item]}</option>`
                                )
                            }

                            break;
                        default:
                            break;
                    }
                }

                $('.select2').select2();
                app.dateTimePicker();
            });
        }
    </script>
@endsection
