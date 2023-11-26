<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate=""
        enctype="multipart/form-data">
    <div class="row">
        @csrf

        @include('back.layouts.core.forms.hidden-input', [
                    'name'          => 'group',
                    'value'         => 'tax',
                    'class'         => '',
                    'required'      => '',
                    'attr'          => '',
        ])

        <div class="col-md-10">
            <div class="card no-padding card-border">
                <div class="card-header">
                    <h4 class="card-title">{{__('Tax Calculation Settings')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row no-tax {{(isset($settings['tax']['tax_rules']) && count($settings['tax']['tax_rules']) ) ? 'hidden' : ''}}">
                        <div class="col-md-12">
                            <div class="alert alert-warning text-center">
                            <strong>{{__("You aren't collecting tax")}}</strong>
                                <p>
                                    {{__('Add your tax rules to start collecting tax.')}}
                                </p>
                            <button onclick="app.enableTaxRules(this)" class="btn btn-primary">{{__('Add Tax Rule')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="loadTaxTemplate">
                        @if (isset($settings['tax']['tax_rules']) && count($settings['tax']['tax_rules']))

                            @include('back.settings.tax.tax-rules')

                        @endif
                    </div>
                </div>
            </div>
        </div>



        <div class="col-md-10">
            <button class="btn btn-success float-right">Save</button>
        </div>
    </div>
</form>
