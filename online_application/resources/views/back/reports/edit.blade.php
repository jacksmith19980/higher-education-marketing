@extends('back.layouts.default')

@section('content')
    <div class="container">
        <input type="hidden" id="current_id" value="1">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h4 class="card-title">{{ __('Add Report') }}</h4>
                        <hr>
                        <form method="POST" action="{{ route('reports.update', $report) }}"
                            class="validation-wizard wizard-circle m-t-40" aria-label="{{ __('Update') }}"
                            data-add-button="{{ __('Update') }}">

                            @method('PUT')
                            @csrf
                            <h6>{{ __('Details') }}</h6>
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
                                        @include('back.layouts.core.forms.text-input', [
                                            'name' => 'name',
                                            'label' => 'Name',
                                            'class' => '',
                                            'required' => true,
                                            'attr' => '',
                                            'value' => $report->name,
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select', [
                                            'name' => 'source',
                                            'label' => 'Data Source',
                                            'placeholder' => 'Select Table',
                                            'class' => '',
                                            'required' => true,
                                            'attr' =>
                                                'id=source-table data-route=' . route('reports.table-columns'),
                                            'value' => $report->source,
                                            'data' => $tables,
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.select', [
                                            'name' => 'category',
                                            'label' => 'Category',
                                            'class' => '',
                                            'required' => true,
                                            'attr' => '',
                                            'value' => $report->category_id,
                                            'data' => $reportCategories,
                                        ])
                                    </div>

                                    <div class="col-md-6">
                                        @include('back.layouts.core.forms.campuses', [
                                            'class' => '',
                                            'required' => true,
                                            'attr' => '',
                                            'value' => '',
                                            'data' => $campuses,
                                        ])
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="description">{{ __('Description') }}<span
                                                class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control mb-2" id="description" required rows="4">{{ $report->description }}</textarea>
                                    </div>
                                </div>
                            </section>
                            <h6>{{ __('Data') }}</h6>
                            <section>
                                <h4 class="card-title">{{ __('Columns') }}</h4>
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.duallistbox', [
                                        'name' => 'columns[]',
                                        'label' => '',
                                        'class' => 'duallistbox',
                                        'required' => true,
                                        'attr' => '',
                                        'id' => 'table-columns',
                                        'value' => $selectedColumns,
                                        'data' => $columns,
                                    ])
                                </div>
                                <div class="row" id="filters">
                                    @php
                                        $filters = json_decode($report->filters);
                                    @endphp
                                </div>
                                <div class="row options_group_transfer">
                                    <div class="col-md-3 offset-9 m-b-20">
                                        @include('back.classrooms._partials.add-slot-button', [
                                            'action' => 'report.addFilter',
                                            'container' => '#filters',
                                            'text' => 'Add Filter',
                                            'dataSource' => 'source',
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

            app.appAjax('GET', null, '{{ route('reports.filters', $report) }}').then(function(
                data) {
                if (data.response == 'success' && data.status == 200) {

                    data.extra.filters.forEach(function(filter) {
                        app.addSlotElements($('#add-filter'))
                    });
                    setTimeout(() => {
                        i = 1
                        data.extra.filters.forEach(function(filter) {
                            $('#column-' + i).val(filter.column).change()
                            dataType = filter.column.split(',')[1]

                            if (dataType.startsWith('text') || dataType.startsWith(
                                    'varchar') || dataType.startsWith('json')) {
                                $('#condition-' + i).empty()
                                    .append('<option value="=">Equals to</option>')
                                    .append('<option value="starts">Starts with</option>')
                                    .append('<option value="ends">Ends with</option>')
                                    .append('<option value="contains">Contains</option>')
                                    .append('<option value="isnull">Is Null</option>')
                                    .val(filter.condition)
                                $('#condition-' + i).parent().parent().parent().find(
                                        '.table-field-value')
                                    .html(
                                        `<input type="text" class="form-control form-control-lg" name="value-${i}" id="value-${i}" autocomplete="off" autocomplete="off" value="${filter.value}" aria-invalid="false">`
                                    )

                            } else if (dataType.startsWith('tinyint')) {
                                $('#condition-' + i).empty()
                                    .append('<option value="=">Is</option>')
                                    .append('<option value="isnull">Is Null</option>')
                                    .val(filter.condition)
                                $('#condition-' + i).parent().parent().parent().find(
                                    '.table-field-value').html(
                                    `<select class="form-control custom-select select2 form-control-lg" name="value-${i}" id="value-${i}" autocomplete="off" required="required"></select>`
                                )
                                $('#condition-' + i).parent().parent().parent().find(
                                        '.table-field-value select')
                                    .append('<option value="true">True</option>')
                                    .append('<option value="false">False</option>')
                                    .val(filter.value)
                                $('.select2').select2()
                            } else if (dataType.startsWith('timestamp')) {
                                $('#condition-' + i).empty()
                                    .append('<option value="=">Equals to</option>')
                                    .append('<option value=">">Greater than</option>')
                                    .append('<option value="<">Lesser than</option>')
                                    .append('<option value="!=">Not equal to</option>')
                                    .append('<option value="isnull">Is Null</option>')
                                    .val(filter.condition)

                                $('#condition-' + i).parent().parent().parent().find(
                                        '.table-field-value')
                                    .html(
                                        `<input type="text" class="datepicker form-control form-control-lg" name="value-${i}" id="value-${i}" autocomplete="off" value="${filter.value}">`
                                    )
                                $('.datepicker').datepicker();
                            } else if (dataType.startsWith('enum')) {
                                dataType = filter.column.replace(filter.column.split(',')[
                                    0] + ',', '')
                                values = dataType.replace('enum(', '').replace(')', '')
                                    .split(',')
                                $('#condition-' + i).empty()
                                    .append('<option value="=">Equals to</option>')
                                    .append('<option value="isnull">Is Null</option>')
                                    .val(filter.condition)
                                $('#condition-' + i).parent().parent().parent().find(
                                    '.table-field-value').html(
                                    `<select class="form-control custom-select select2 form-control-lg" name="value-${i}" id="value-${i}" autocomplete="off" required="required"></select>`
                                )
                                values.forEach(element => {
                                    element = element.replaceAll("'", '')
                                    $('#condition-' + i).parent().parent().parent()
                                        .find('select')
                                        .append(
                                            `<option value="${element}">${element}</option>`
                                        )
                                });
                                $('#value-' + i).val(filter.value)
                            } else if (dataType.startsWith('int')) {
                                $('#condition-' + i).empty()
                                    .append('<option value="=">Equals to</option>')
                                    .append('<option value=">">Greater than</option>')
                                    .append('<option value="<">Lesser than</option>')
                                    .append('<option value="!=">Not equal to</option>')
                                    .append('<option value="isnull">Is Null</option>')
                                    .val(filter.condition)
                                $('#condition-' + i).parent().parent().parent().find(
                                    '.table-field-value').
                                html(
                                    `<input type="number" class="form-control form-control-lg" name="value-${i}" id="value-${i}" autocomplete="off" value="${filter.value}" aria-invalid="false">`
                                )
                            }

                            i += 1
                        });
                    }, 2000);
                }
            });
            $('.select2').select2()
        })
    </script>
@endsection
