<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

    <div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

        <div class="col-md-4">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'column-' . $id,
            'label'     => '' ,
            'class'     => 'table-field' ,
            'value'     => isset($column) ? $column : '',
            'required'  => false,
            'attr'      => '',
            'data'      => isset($columns) ? $columns : []
        ])
        </div>

        <div class="col-md-3">
        @include('back.layouts.core.forms.select',
        [
            'name'      => 'condition-' . $id,
            'label'     => '' ,
            'class'     => '' ,
            'value'     => isset($condition) ? $condition : '',
            'required'  => false,
            'attr'      => '',
            'data'      => []
        ])
        </div>

        <div class="col-md-4 table-field-value">
        </div>

        <div class="col-md-1 action_wrapper">
            <div class="form-group m-t-27">
                <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>

    </div>

<script>
$(document).ready(function () {
    $('.select2').trigger('change.select2');
    $('.table-field').on('select2:select', function (e) {
        fieldName = $(e.target).val().split(',')[0]
        dataType = $(e.target).val().replace(fieldName + ',', '')
        if(dataType.startsWith('text') || dataType.startsWith('varchar') || dataType.startsWith('json')){
            $(e.target).parent().parent().parent().find('.table-field-value').html('<input type="text" class="form-control form-control-lg" name="value[]" autocomplete="off" aria-invalid="false">')
            $(e.target).parent().parent().next().find('select').empty()
                .append('<option value="=">Equals to</option>')
                .append('<option value="starts">Starts with</option>')
                .append('<option value="ends">Ends with</option>')
                .append('<option value="contains">Contains</option>')
                .append('<option value="isnull">Is Null</option>')

        }else if(dataType.startsWith('int')){
            $(e.target).parent().parent().parent().find('.table-field-value').html('<input type="number" class="form-control form-control-lg" name="value[]" autocomplete="off" aria-invalid="false">')
            $(e.target).parent().parent().next().find('select').empty()
                .append('<option value="=">Equals to</option>')
                .append('<option value=">">Greater than</option>')
                .append('<option value="<">Lesser than</option>')
                .append('<option value="!=">Not equal to</option>')
                .append('<option value="isnull">Is Null</option>')

        }else if(dataType.startsWith('tinyint')){
            values = ['True', 'False']
            $(e.target).parent().parent().next().find('select').empty()
                .append('<option value="=">Is</option>')
                .append('<option value="isnull">Is Null</option>')
            $(e.target).parent().parent().parent().find('.table-field-value').html('<select class="form-control custom-select select2 form-control-lg" name="value[]" autocomplete="off" required="required"></select>')
            values.forEach(element => {
                $(e.target).parent().parent().next().next().find('select')
                .append(`<option value="${element.toLowerCase()}">${element}</option>`)
            });
        }else if(dataType.startsWith('timestamp')){
            $(e.target).parent().parent().parent().find('.table-field-value').html('<input type="text" class="datepicker form-control form-control-lg" name="value[]" autocomplete="off">')
            $('.datepicker').datepicker();
            $(e.target).parent().parent().next().find('select').empty()
                .append('<option value="=">Equals to</option>')
                .append('<option value=">">Greater than</option>')
                .append('<option value="<">Lesser than</option>')
                .append('<option value="!=">Not equal to</option>')
                .append('<option value="isnull">Is Null</option>')
        }else if(dataType.startsWith('enum')){
            values = dataType.replace('enum(', '').replace(')', '').split(',')
            $(e.target).parent().parent().next().find('select').empty()
                .append('<option value="=">Equals to</option>')
                .append('<option value="isnull">Is Null</option>')
            $(e.target).parent().parent().parent().find('.table-field-value').html('<select class="form-control custom-select select2 form-control-lg" name="value[]" autocomplete="off" required="required"></select>')
            values.forEach(element => {
                element = element.replaceAll("'", '')
                $(e.target).parent().parent().next().next().find('select')
                .append(`<option value="${element}">${element}</option>`)
            });
        }else{
            console.log('unsupported data type')
        }
    });
});
</script>
</div>