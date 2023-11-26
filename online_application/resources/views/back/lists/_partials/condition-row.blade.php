<div class="row">
    <div class="col-md-2">
        @include('back.layouts.core.forms.select', [
            'name' => 'link_' . $i,
            'class' => '',
            'required' => true,
            'attr' => '',
            'value' => 'and',
            'data' => [
                'and' => __('AND'),
                'or' => __('OR')],
        ])
    </div>

    <div class="col-md-3">
        @include('back.layouts.core.forms.select', [
            'name' => 'field_' . $i,
            'class' => '',
            'placeholder' => 'Choose Field',
            'required' => true,
            'attr' => 'onchange=getFieldDetails(' . $i . ')',
            'value' => '',
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

    <div class="col-md-4" id="value_container_{{$i}}">
        @include('back.layouts.core.forms.select', [
            'name' => 'value_' . $i,
            'class' => '',
            'required' => true,
            'attr' => '',
            'value' => '',
            'data' => [],
        ])
    </div>

    <div class="col-md-1 action_wrapper">
        <div class="form-group">
            <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'row')">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
</div>
