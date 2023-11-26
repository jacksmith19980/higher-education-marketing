<div class="row tax-rule" data-index="{{$index}}">
    <div class="col-md-5">
        @include('back.layouts.core.forms.text-input', [
            'name' => 'tax_rules['. $index .'][label]',
            'label' => 'Label' ,
            'class' => '',
            'required' => false,
            'attr' =>'',
            'value' =>($rule) ? $rule['label'] : '',
            ])
    </div>
    <div class="col-md-5">
        @include('back.layouts.core.forms.number', [
            'name' => 'tax_rules['. $index .'][value]',
            'label' => 'Value' ,
            'class' => '',
            'step'  => '0.001',
            'required' => false,
            'attr' => '',
            'value' =>($rule) ? $rule['value'] : '',
            ])
    </div>
    <div class="col-md-2" style="padding-top: 34px;">
        <button class="btn btn-danger float-right" onclick="app.removeTaxRule({{$index}})">{{__('Remove')}}</button>
    </div>
</div>