<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info handle">
            <h4 class="m-b-0 text-white">{{__('Integrations - Mautic')}}</h4>
        </div>
        <div class="card-body row">

            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name' => 'properties[integrations][mautic][request_type]',
                    'label' => 'Quote Request Type' ,
                    'class' =>'select2' ,
                    'required' => false,
                    'attr' => '',
                    'value' => (isset($assistantBuilder->properties['integrations']['mautic']['request_type'])) ?
                    $assistantBuilder->properties['integrations']['mautic']['request_type'] : [],
                    'data' => MauticHelper::getRequestType()
                ])
            </div>
            <div class="col-md-6">
                @include('back.layouts.core.forms.select',
                [
                    'name' => 'properties[integrations][mautic][contact_type]',
                    'label' => 'Contact Type' ,
                    'class' =>'select2' ,
                    'required' => false,
                    'attr' => '',
                    'value' => (isset($assistantBuilder->properties['integrations']['mautic']['contact_type'])) ?
                    $assistantBuilder->properties['integrations']['mautic']['contact_type'] : [],
                    'data' => MauticHelper::getContactTypes()
                ])
            </div>

        </div>
    </div>
</div>