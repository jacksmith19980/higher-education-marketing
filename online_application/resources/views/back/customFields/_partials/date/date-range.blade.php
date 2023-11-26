<div class="row">
    <div class="col-md-6">
        @include('back.layouts.core.forms.date-input',
        [
        'name' => 'properties[startDate]',
        'label' => 'Start Date' ,
        'class' =>'ajax-form-field years-only' ,
        'required' => false,
        'attr' => '',
        'value' =>  (isset($customfield->properties['startDate'])) ? $customfield->properties['startDate'] : ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.date-input',
        [
        'name' => 'properties[endDate]',
        'label' => 'End Date' ,
        'class' =>'ajax-form-field years-only' ,
        'required' => false,
        'attr' => '',
        'value' => (isset($customfield->properties['endDate'])) ? $customfield->properties['endDate'] : ''
        ])
    </div>
</div>
<script type="text/javascript">
    app.dateTimePicker();
</script>
