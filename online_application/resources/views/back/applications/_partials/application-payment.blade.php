<h6>{{__("Payment")}}</h6>
<section>
    <div class="btn-group float-right" role="group">
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                &nbsp;&nbsp;&nbsp;&nbsp;{{__("New Payment Type")}}&nbsp;&nbsp;&nbsp;&nbsp;
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                <a class="dropdown-item" href="#" onclick="app.paymentType('fullAmount', 'full-amount')">{{__('Full Amount')}}</a>

                <a class="dropdown-item" onclick="app.paymentType('fixedAmount', 'fixed-amount')" href="#">{{__('Installments Fixed Amount')}}</a>

                <a class="dropdown-item" onclick="app.paymentType('variableAmount', 'variable-amount')" href="#">{{__('Installments Variable Amount')}}</a>
            </div>
        </div>
    </div>

    <div class="w-100">&nbsp;</div>
    <br>
    <div class="w-100">&nbsp;</div>
    <br>
    <div class="w-100">&nbsp;</div>

    <div class="row">
        <div class="col-md-6">
            @include('back.layouts.core.forms.multi-select',[
                'name' => 'properties[payment_method][]',
                'label' => 'Payment Method',
                'class' => 'select2',
                'required' => false,
                'attr' => '',
                'value' => (isset($application->properties['payment_method']))? $application->properties['payment_method'] : '',
                'placeholder' => 'Select a Payment Method',
                'data' => [
                    'credit_card' => 'Credit Card',
                    'bank_transfers' => 'Bank Transfers',
                    'direct_deposit' => 'Direct Deposit',
                    'cash' => 'Cash'
                ]
            ])
        </div>

        <div class="col-md-6">
            @include('back.layouts.core.forms.select',[
                'name' => 'properties[tax_on]',
                'label' => 'Tax On',
                'class' => 'select2',
                'required' => false,
                'attr' => '',
                'value' => (isset($application->properties['tax_on']))? $application->properties['tax_on'] : '',
                'placeholder' => 'Apply tax on',
                'data' => [
                    'no_tax' => 'No Tax',
                    'total_invoice' => 'Total Invoice',
                    'tuition_only' => 'Tuition only',
                    'application_fees' => 'Application Fees',
                    'add-ons' => 'Add-ons'
                ]
            ])
        </div>
    </div>
    <div class="full-amount row">
        @if(key_exists('full_amount', $application->properties))
            @include('back.applications._partials.application-creation.shared.full-amount')
        @endif
    </div>

    <div class="fixed-amount row">
        @if(key_exists('fixed_amount', $application->properties))
            @include('back.applications._partials.application-creation.shared.installment-fix-amount')
        @endif
    </div>

    <div class="variable-amount row">
        @if(key_exists('variable_amount', $application->properties))
            @include('back.applications._partials.application-creation.shared.installment-variable-amount')
        @endif
    </div>
</section>
