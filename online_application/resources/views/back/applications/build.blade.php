@extends('back.layouts.default')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-9 col-sm-9">
        <input type="hidden" name="sections_order" value="{{json_encode($application->sections_order)}}">


        <div class="row draggable-cards sections-container" id="draggable-area">
            @if(isset($application->sections_order))
            @foreach ($application->sections_order as $sectionId)
            @php
            $section = $sections->filter(function($item) use ($sectionId) {
            return $item->id == $sectionId;
            })->first();
            @endphp

            @include('back.applications._partials.section', ['section' => $section , 'application' => $application])
            @endforeach
            @endif

            @if(count($application->integrations))
            @foreach ($application->integrations as $integration)
            @include('back.applications._partials.integration' , ['application' => $application , 'integration' =>
            $integration ] )
            @endforeach
            @endif

            @if(count($application->PaymentGateways))
            @foreach ($application->PaymentGateways as $PaymentGateway)
            @include('back.applications._partials.payments' , ['application' => $application , 'paymentGateWay' =>
            $PaymentGateway ] )
            @endforeach
            @endif

            @if(count($application->actions))
            @foreach ($application->actions as $action)
            @include('back.applications._partials.action' , ['application' => $application , 'action' => $action ] )
            @endforeach
            @endif
        </div>
    </div>




    <div class="col-md-3 col-sm-3">

        <div class="card">

            <div class="card-header bg-info handle">
                <h4 class="text-white m-b-0 handle">{{$application->title}}</h4>
            </div>

            <div class="card-body">
                <div class="input-group">
                    <input type="text"
                        value="{{ ApplicationHelpers::getApplicationUrl($application, $school, $settings)}} "
                        class="form-control " disabled aria-label="Text input with dropdown button">
                    <div class="actions-button">
                        @include('back.layouts.core.helpers.table-actions' , [
                        'show_check_box' => false,
                        {{-- 'title' => "Actions", --}}
                        'buttons'=> [
                        'view' => [
                        'text' => 'View',
                        'icon' => 'icon-eye',
                        'attr' => "onclick=app.redirect('". ApplicationHelpers::getApplicationUrl($application, $school, $settings) ."',true)",
                        'class' => 'view-application',
                        ],
                        'preview' => [
                        'text' => 'Preview',
                        'icon' => 'icon-screen-desktop',
                        'attr' => "onclick=app.redirect('".route('application.preview' , ['school'=> $school ,
                        'application' => $application])."',true)",
                        'class' => 'view-application',
                        ],
                        'pdf-preview' => [
                        'text' => 'PDF Preview',
                        'icon' => 'far fa-file-pdf',
                        'attr' => "onclick=app.redirect('".route('application.pdf' , ['application' => $application, 'action' => 'view'])."',true)",
                        'class' => '',
                        ],
                        'edit' => [
                        'text' => 'Edit',
                        'icon' => 'icon-note',
                        'attr' => "onclick=app.redirect('".route('applications.edit' , $application)."')",
                        'class' => '',
                        ],
                        'delete' => [
                        'text' => 'Delete',
                        'icon' => 'icon-trash text-danger',
                        'attr' => 'onclick=app.deleteElement("'.route('applications.destroy' ,
                        $application).'","","data-application-id")',
                        'class' => '',
                        ],
                        ]
                        ])
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info handle">
                <h4 class="text-white m-b-0 handle">{{__('Application Builder')}}</h4>
            </div>

            <div class="card-body fields-list" id="fields-list">
                @include('back.applications.builder.form-builder' , [

                'fieldsType' => ApplicationHelpers::getFieldTypes() ,

                'paymentGatways' => ApplicationHelpers::getPaymentGateways(),

                'integrations' => ApplicationHelpers::getIntegrations(),

                'actions' => ApplicationHelpers::getActions(),
                ])
            </div>
        </div>
    </div>
</div>

@endsection
