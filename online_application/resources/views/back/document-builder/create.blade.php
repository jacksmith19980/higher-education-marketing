@extends('back.layouts.default')

@section('content')
    <style>
        .media-body {
            padding-top: 0 !important
        }
    </style>
    <div class="container">
        <input type="hidden" id="current_id" value="1">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{ __('Add Document Builder') }}</h4>
                        <hr>
                        <form method="POST" action="{{ route('documentBuilder.store') }}"
                            class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Add New') }}"
                            data-add-button="{{ __('Add New') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.file-input', [
                                        'name' => 'pdf-document',
                                        'label' => 'Document',
                                        'class' => '',
                                        'required' => false,
                                        'attr' => '',
                                        'value' => '',
                                    ])
                                </div>

                                <div class="col-md-2 offset-10 text-right">
                                    <button class="btn btn-primary" id="upload-pdf" type="button">{{ __('Upload') }}</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6" style="display: none;">
                                    @include('back.layouts.core.forms.text-input', [
                                        'name' => 'pdf-filename',
                                        'label' => 'PDF filename',
                                        'class' => '',
                                        'required' => true,
                                        'attr' => '',
                                        'value' => '',
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input', [
                                        'name' => 'pdf-title',
                                        'label' => 'Title',
                                        'class' => '',
                                        'required' => true,
                                        'attr' => '',
                                        'value' => '',
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.select', [
                                        'name' => 'selector-table',
                                        'id' => 'selector-table',
                                        'label' => 'Selector',
                                        'class' => '',
                                        'required' => true,
                                        'attr' => 'id=source data-route=' . route('reports.table-columns'),
                                        'value' => '',
                                        'data' => [
                                            'students' => __('Students'),
                                            'instructors' => __('Instructors'),
                                        ],
                                    ])
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-area',
                                    [
                                    'name' => 'description',
                                    'label' => 'Description' ,
                                    'class' =>'' ,
                                    'required' => false,
                                    'attr' => '',
                                    'value' =>  ''
                                    ])
                                </div>

                            </div>

                            <div class="row pt-5 px-2">
                                <div class="col-md-3">
                                    {{ __('Table') }}
                                </div>
                                <div class="col-md-3">
                                    {{ __('Field') }}
                                </div>
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-3">
                                    {{ __('Document Field') }}
                                </div>
                            </div>

                            <div class="px-2 pb-5" id="pdf-fields">
                            </div>

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

            $('#upload-pdf').click(
                e => {
                    $('#pdf-fields').empty()
                    fileData = new FormData();
                    if ($('#pdf-document').prop('files').length > 0) {
                        file = $('#pdf-document').prop('files')[0];
                        fileData.append("file", file);
                        table = $('select[name="selector-table"]').val();
                        fileData.append("table", table);
                    }
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                            '_token': $('meta[name="_token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ route('documentBuilder.pdfInfo') }}',
                        type: "POST",
                        data: fileData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data.response === 'success' && data.status === 200) {
                                $('#pdf-title').val(data.extra.title)
                                $('#pdf-filename').val(data.extra.filename)
                                let tables = Object.entries(data.extra.tables).map(([key, value]) =>
                                    `<option value="${key}">${value}</option>`).join('');

                                let i = 0;
                                data.extra.textFields.forEach((textField, index) => {
                                    $('#pdf-fields').append(`
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" onChange="pdfTableChanged(this)" id="table-${index}" name="table-${index}">
                                                    <option value=""></option>
                                                    ${tables}
                                                    <option value="custom">{{ __('Custom') }}</option>
                                                    <option value="settings">{{ __('Settings') }}</option>
                                                    <option value="special">{{ __('Special') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" id="value-${index}" name="value-${index}" target="${textField.name}">
                                                </select>
                                                <input id="text-${index}" class="form-control" style="display: none" type="text" name="text-${index}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" id="format-${index}" name="format-${index}">
                                                    <option value=""></option>
                                                    <option value="uppercase">{{ __('Uppercase') }}</option>
                                                    <option value="lowercase">{{ __('Lowercase') }}</option>
                                                    <option value="capitalize">{{ __('Capitalize') }}</option>
                                                    <option value="d">01 -> 31</option>
                                                    <option value="m">01 -> 12</option>
                                                    <option value="y">99</option>
                                                    <option value="Y">1999</option>
                                                    <option value="Y-m-d">YYYY-MM-DD</option>
                                                    <option value="d-m-Y">DD-MM-YYYY</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control" value="${textField.name.split('.').at(-1).replace('[0]', '')}">
                                                <input type="hidden" name="name-${index}" value="${textField.name}">
                                            </div>
                                        </div>
                                    </div>
                                `)
                                    i = index + 1
                                })

                                data.extra.signatureFields.forEach((signatureField, index) => {
                                    $('#pdf-fields').append(`
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" onChange="pdfTableChanged(this)" id="table-${index + i}" name="table-${index + i}">
                                                    <option value=""></option>
                                                    ${tables}
                                                    <option value="custom">{{ __('Custom') }}</option>
                                                    <option value="settings">{{ __('Settings') }}</option>
                                                    <option value="special">{{ __('Special') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" id="value-${index + i}" name="value-${index + i}" target="${signatureField.name}">
                                                </select>
                                                <input id="text-${index + i}" class="form-control" style="display: none" type="text" name="text-${index + i}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" id="format-${index + i}" name="format-${index + i}">
                                                    <option value=""></option>
                                                    <option value="uppercase">{{ __('Uppercase') }}</option>
                                                    <option value="lowercase">{{ __('Lowercase') }}</option>
                                                    <option value="capitalize">{{ __('Capitalize') }}</option>
                                                    <option value="d">01 -> 31</option>
                                                    <option value="m">01 -> 12</option>
                                                    <option value="y">99</option>
                                                    <option value="Y">1999</option>
                                                    <option value="Y-m-d">YYYY-MM-DD</option>
                                                    <option value="d-m-Y">DD-MM-YYYY</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control" value="${signatureField.name.split('.').at(-1).replace('[0]', '')}">
                                                <input type="hidden" name="name-${index + i}" value="${signatureField.name}">
                                            </div>
                                        </div>
                                    </div>
                                `)
                                    i = index + i + 1
                                })

                                data.extra.choiceFields.forEach((choiceField, index) => {
                                    let choices = choiceField.items.map(item =>
                                        `<option value="${item}">${item}</option>`)
                                    $('#pdf-fields').append(`
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control select2" id="value-${index + i}" name="value-${index + i}">
                                                    ${choices}
                                                </select>
                                                <input type="hidden" name="choices-${index + i}" value="${choiceField.items.join(',')}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input readonly type="text" class="form-control" value="${choiceField.name.split('.').at(-1).replace('[0]', '')}">
                                                <input type="hidden" name="name-${index + i}" value="${choiceField.name}">
                                            </div>
                                        </div>
                                    </div>
                                `)
                                })

                                $('.select2').select2()

                            }
                        }
                    });
                }
            );

            pdfTableChanged = (el) => {
                if ($(el).val() === 'custom') {
                    $('#' + $(el).attr('id').replace('table-', 'value-')).next(".select2-container").hide();
                    $('#' + $(el).attr('id').replace('table-', 'text-')).css('display', 'block')
                } else {
                    $('#' + $(el).attr('id').replace('table-', 'value-')).next(".select2-container").show();
                    $('#' + $(el).attr('id').replace('table-', 'text-')).css('display', 'none')

                    var data = {
                        action: 'documentBuilder.getFields',
                        payload: {
                            table: $(el).val()
                        }
                    };
                    app.appAjax('POST', data, app.ajaxRoute).then(function(data) {
                        $('#' + $(el).attr('id').replace('table-', 'value-')).empty()
                        data.extra.columns.forEach(item => {
                            $('#' + $(el).attr('id').replace('table-', 'value-')).append(
                                `<option value="${item.name}">${item.label}</option>`)
                        })
                    });
                }
            }
        })
    </script>
@endsection
