<div style="display: flex; flex-wrap: wrap;" class="col-md-12 date-row">
	<div class="col-md-3">
		@include('back.layouts.core.forms.date-input',
            [
            'name'  => "properties[$order][date]",
            'label' => 'Date' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => (isset($date->start)) ? date('Y-m-d' , strtotime($date->start)) : '',
            'data' => ''
            ])
	</div>
	<div class="col-md-3">
		@include('back.layouts.core.forms.time-input',
            [
            'name' => "properties[$order][start_time]",
            'label' => 'Start time' ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'value' => (isset($date->start)) ? date('h:i' , strtotime($date->start)) : '',
            'data' => ''
            ])
	</div>
	<div class="col-md-3">
		@include('back.layouts.core.forms.time-input',
            [
            'name'          => "properties[$order][end_time]",
            'label'         => 'End time' ,
            'class'         => 'ajax-form-field' ,
            'required'      => true,
            'attr'          => '',
            'value'         => (isset($date->end)) ? date('h:i' , strtotime($date->end)) : '',
            'data' => ''
            ])
	</div>
	<div class="col-md-{{(isset($date->price) ? 3 : 2)}}">
        @include('back.layouts.core.forms.text-input',
    [
        'name'          => "properties[$order][price]",
        'label'         => 'Price' ,
        'class'         => 'ajax-form-field' ,
        'required'      => false,
        'attr'          => '',
        'value'         => (isset($date->price)) ? $date->price : '',
        'data'          => '',
        'hint_after'    => isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD'
    ])
    </div>
    @if(!isset($date))
        <div class="col-md-1 action_wrapper">
            <div class="form-group m-t-27">
                <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'date-row')">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
    @endif
</div>
