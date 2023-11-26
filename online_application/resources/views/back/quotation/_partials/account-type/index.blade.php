<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info handle">
            <h4 class="m-b-0 text-white">{{__('Account Type')}}</h4>
        </div>
        <div class="card-body row">

            <div class="col-md-6">
                @include('back.layouts.core.forms.multi-select',
                [
                    'name' => 'properties[account_type][]',
                    'label' => 'Account Type' ,
                    'class' =>'select2' ,
                    'required' => false,
                    'attr' => '',
                    'value' => (isset($quotation->properties['account_type'])) ?
                    $quotation->properties['account_type'] : [],
                    'data' => [
                        'student'=> 'Student',
                        'parent'=> 'Parent',
                        'agent'=> 'Agent',
                    ]
                ])
            </div>

        </div>
    </div>
</div>