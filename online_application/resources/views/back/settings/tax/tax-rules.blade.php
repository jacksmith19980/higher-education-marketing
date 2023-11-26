<div class="row">
<div class="col-md-12">
    @include('back.layouts.core.forms.select', [
    'name' => 'tax_calculation_based_on',
    'label' => 'Tax Calculation Based On' ,
    'placeholder' => '-- Select --' ,
    'class' => '',
    'required' => false,
    'attr' => '',
    'data' => [
        'item'      => 'Item Price',
        'row'       => 'Row Total', // The total of the line item in the order, less discounts
        'total'     => 'The Order Total',
    ],
    'value' => isset($settings['tax']['tax_calculation_based_on'])? $settings['tax']['tax_calculation_based_on'] : 'disabled',
    ])
</div>

    <div class="col-md-12">
        <h5 class="pt-1 pb-2">{{__('Tax Rules')}}</h5>
    <button class="btn btn-success float-right" onclick="app.addTaxRule(this)">{{__('Add Rule')}}</button>
    </div>
</div>
<div class="tax-rules">

    @if (isset($settings['tax']['tax_rules']) && count($settings['tax']['tax_rules']))
        @foreach ($settings['tax']['tax_rules'] as $key => $rule)
            @include('back.settings.tax._partials.tax-rule' , ['index' =>$key , 'rule' => $rule])
        @endforeach

    @else

        @include('back.settings.tax._partials.tax-rule' , ['index' => 0 , 'rule' => false])
    @endif
</div>
