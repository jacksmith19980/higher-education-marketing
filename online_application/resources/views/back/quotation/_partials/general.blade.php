<div class="row">
    @if (!count($courses))
        <div class="alert alert-danger col-12">
            {{ __('You don\'t have any courses, Please add course first') }}
        </div>
    @endif
</div>
<div class="row">


    <div class="col-md-6">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     =>'' ,
            'required'  => true,
            'attr'      => '',
            'value'     => (isset($quotation->title)) ? $quotation->title : '',
            'data'      => ''
        ])
    </div>

    <div class="col-md-6">
        @include('back.layouts.core.forms.select',
            [
                'name'      => 'application',
                'label'     => 'Application' ,
                'class'     =>'select2' ,
                'required'  => true,
                'attr'      => '',
                'value'     => (isset($quotation->application_id)) ? $quotation->application_id : '',
                'placeholder' => 'Select Application',
                'data'      => $applications
                ])
    </div>


    <div class="col-md-12">
        @include('back.layouts.core.forms.text-area',
        [
            'name'      => 'description',
            'label'     => 'Description' ,
            'class'     =>'' ,
            'required'  => false,
            'attr'      => '',
            'value'     => (isset($quotation->description)) ? $quotation->description : '',
            'data'      => ''
        ])
    </div>
    <div class="col-md-6">
        @include('back.layouts.core.forms.multi-select',
        [
            'name' => 'properties[account_type][]',
            'label' => 'Account Type' ,
            'class' =>'select2' ,
            'required' => false,
            'attr' => '',
            'value' => (isset($quotation->properties['account_type'])) ?
            $quotation->properties['account_type'] : ['Student'],
            'data' => [
                'student'=> 'Student',
                'parent'=> 'Parent',
                'agent'=> 'Agent',
            ]
        ])
    </div>

    <div class="container">
        
        @if(isset($settings['integrations']))
           {{--  <h5 class="m-10">{{__('Integrations')}}</h5> --}}
            <div class="row w-100" id="quotation-draggable-area">
                @if(isset($settings['integrations']['integration_mautic']))
                    @include('back.quotation._partials.integrations.mautic.index')
                @endif
            </div>
        @endif


        {{-- <h5 class="m-10">{{__('Quotation Account type')}}</h5> --}}
        {{-- <div class="row w-100" id="quotation-draggable-area">
            @include('back.quotation._partials.account-type.index')
        </div> --}}

        {{-- <h5 class="m-10">{{__('Quotation Flow')}}</h5> --}}

        <div class="row w-100" id="quotation-draggable-area">

            @php
                $blocks = QuotationHelpers::getDefaultFlowOrder();
            @endphp


            <input type="hidden" name="properties[orders]" value="{{json_encode($blocks)}}"/>

            @foreach ($blocks as $block)

                @include('back.quotation._partials.'.$block.'.index')

            @endforeach

            @include('back.quotation._partials.thank_you.index')

        </div>

    </div> <!-- row -->
</div>