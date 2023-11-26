<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
      <div class="col-md-3">
            @include('back.layouts.core.forms.date-input',
            [
            'name'  => 'properties[date][]',
            'label' => 'Date' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => '',
            'data' => ''
            ])
      </div>
      <div class="col-md-3">
            @include('back.layouts.core.forms.time-input',
            [
            'name' => 'properties[start_time][]',
            'label' => 'Start time' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => '',
            'data' => ''
            ])
      </div>
      <div class="col-md-3">
            @include('back.layouts.core.forms.time-input',
            [
            'name' => 'properties[end_time][]',
            'label' => 'End time' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => '',
            'data' => ''
            ])
      </div>
      <div class="col-md-2">
            @include('back.layouts.core.forms.text-input',
            [
            'name' => 'properties[date_price][]',
            'label' => 'Price' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
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