<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">

    <div class="col-md-3">
        @include('back.layouts.core.forms.number', [
            'name' => 'start_date_' . $i,
            'label' => 'From Week',
            'class' => '',
            'required' => true,
            'attr' => 'autocomplete=off onfocusout=verifyPricingDates(event) min=1 max=52',
            'value' => '',
            'data' => '',
        ])
    </div>

    <div class="col-md-3">
        @include('back.layouts.core.forms.number', [
            'name' => 'end_date_' . $i,
            'label' => 'To Week',
            'class' => '',
            'required' => true,
            'attr' => 'autocomplete=off onfocusout=verifyPricingDates(event) min=1 max=52',
            'value' => '',
            'data' => '',
        ])
    </div>

    <div class="col-md-4">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'value_' . $i,
            'label' => 'Value',
            'class' => '',
            'required' => true,
            'attr' => 'autocomplete=off',
            'value' => '',
            'data' => '',
        ])
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
    $(document).ready(function() {
        $('.datepicker').datepicker()

    });
</script>
