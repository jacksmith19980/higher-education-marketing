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
                            aria-label="{{ __('Update') }}" data-add-button="{{ __('Update') }}">
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
                                            'value' => $list->name,
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
                                            'value' => $list->is_active,
                                            'default' => true,
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="description">{{ __('Description') }}<span
                                                class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control mb-2" id="description" required rows="4">{{ $list->description }}</textarea>
                                    </div>
                                </div>
                            </section>

                            <h6>{{ __('Filters') }}</h6>
                            <section>

                                @php
                                    $i = 0;
                                    $conditions = json_decode($list->query)->where;
                                @endphp

                                <div class="" id="lists_wrapper">
                                    @foreach ($conditions as $condition)
                                        <div class="row">

                                            <div class="col-md-2">
                                                @if ($condition->link)
                                                    @include('back.layouts.core.forms.select', [
                                                        'name' => 'link_' . $i,
                                                        'class' => '',
                                                        'required' => true,
                                                        'attr' => '',
                                                        'value' => 'and',
                                                        'data' => [
                                                            'and' => __('AND'),
                                                            'or' => __('OR'),
                                                        ],
                                                    ])
                                                @else
                                                    {{ __('Add Filters') }}:
                                                @endif
                                            </div>

                                            <div class="col-md-3">
                                                @include('back.layouts.core.forms.select', [
                                                    'name' => 'field_' . $i,
                                                    'class' => '',
                                                    'placeholder' => 'Choose Field',
                                                    'required' => true,
                                                    'attr' => 'onchange=getFieldDetails(' . $i . ')',
                                                    'value' => $condition->field,
                                                    'data' => $columns,
                                                ])
                                            </div>

                                            <div class="col-md-2">
                                                @include('back.layouts.core.forms.select', [
                                                    'name' => 'condition_' . $i,
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
                                                    'name' => 'value_' . $i,
                                                    'class' => '',
                                                    'required' => true,
                                                    'attr' => '',
                                                    'value' => '',
                                                    'data' => [],
                                                ])
                                            </div>
                                        </div>
                                        @php
                                            
                                            $i += 1;
                                        @endphp
                                    @endforeach


                                </div>
                                <input type="hidden" id="current_id" name="current_id" value="{{ count($conditions) }}">
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
        $(document).ready(function() {
            $('.select2').trigger('change.select2');
            setTimeout(loadList, 2000);
        })

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
                                $('#value_' + id).append(
                                    `<option value="${item}">${data.extra.data[item]}</option>`
                                )
                            }

                            break;
                        default:
                            break;
                    }
                }
            });
        }

        var loadList = (el) => {
            id = {{ $list->id }};

            var data = {
                action: 'liste.getList',
                payload: {
                    id,
                }
            };

            app.appAjax('POST', data, app.ajaxRoute).then(data => {
                if (data.response == 'success' && data.status == 200) {
                    i = 0
                    JSON.parse(data.extra.list.query)['where'].forEach(e => {
                        document.querySelector('#condition_' + i).value = e.name
                        if (document.querySelector('#value_' + i).tagName === 'SELECT') {
                            $('#value_' + i).val(e.value)
                            $('#value_' + i).trigger('change')
                        } else {
                            document.querySelector('#value_' + i).value = e.value
                        }
                        $('#condition_' + i).trigger('change')

                        if(e.link){
                            $('#link_' + i).val(e.link)
                            $('#link_' + i).trigger('change')
                        }
                        i++;
                    })
                }
            })
        }
    </script>
@endsection
