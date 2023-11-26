@extends('back.layouts.core.helpers.applications-table' , [
'show_buttons' => $permissions['create|application'],
'title' => 'Application Forms',
])

@section('table-content')
@if ($applications)
<div class="application-cards">
    @foreach ($applications as $application)
    @php
        $campuses = $application->campuses->toArray();
    @endphp
    <div class="application-card" id="{{$application->id}}" data-application-id="{{$application->id}}">
        <h4 class="ap-title ap-col">{{$application->title}} <br/>
            @if(count($campuses))
                <span class="mt-2">
                    <small>
                        {{implode(", " , array_column( $campuses , 'title'))}}
                    </small>
                </span>
            @endif
        </h4>
        <div class="ap-pausable ap-col approve-bullet">
            <span class="ap-icontext">
                <i style="cursor: pointer" onclick="app.toggleApplicationPublishStatus(this)"
                    data-application-id='{{$application->id}}'
                    class="approved fa fa-circle text-success {{(!$application->published) ? 'hidden' : ''}}"
                    data-toggle="tooltip" data-placement="top" data-code-id="1" title=""
                    data-original-title="Publishable"></i>
                <i style="cursor: pointer" onclick="app.toggleApplicationPublishStatus(this)"
                    data-application-id='{{$application->id}}'
                    class="unapporved fa fa-circle text-danger {{($application->published) ? 'hidden' : ''}}"
                    data-toggle="tooltip" data-placement="top" data-code-id="1" title=""
                    data-original-title="Unpublishable"></i>
            </span>
            <span data-application-id="{{$application->id}}"
                class="ap-coltext {{($application->published) ? 'publishable' : 'unpublishable'}}">
                {{($application->published) ? __('Published') : __('Unpublished')}}
            </span>
        </div>

        <div class="ap-cstatus ap-col">
            <span class="ap-icontext">
                <i class="icon-reload"></i>
                <strong>{{ $application->status_count['Started'] }}</strong>
            </span>
            <span class="ap-coltext">{{__('Incomplete')}}</span>
        </div>

        <div class="ap-sstatus ap-col">
            <span class="ap-icontext">
                <i class="far fa-arrow-alt-circle-right"></i>
                <strong>{{ $application->status_count['Submitted'] }}</strong>
            </span>
            <span class="ap-coltext">{{__('Submitted')}}</span>
        </div>

        @php
        $invoice_exist = false;

        if (count($application->invoices)) {
        $invoice_exist = true;

        }
        @endphp
        @if ($invoice_exist)
        <div class="ap-rstatus ap-col">
            <span class="ap-icontext">
                <i class="far fa-check-circle"></i>
                <strong>{{ $application->status_count['Paid'] }}</strong>
            </span>
            <span class="ap-coltext">{{__('Registration Paid')}}</span>
        </div>
        @else
        <div class="ap-rstatus ap-col">
            <span class="ap-icontext">
                <i class="far fa-check-circle"></i>
            </span>
            <span class="ap-coltext">{{__('N/A')}}</span>
        </div>
        @endif

        <div class="ap-stotal ap-col">
            <span class="ap-icontext">
                <i class="far fa-star"></i>
                <strong>{{ count($application->submissions) }}</strong>
            </span>
            <span class="ap-coltext">{{__('Total')}}</span>
        </div>

        <div class="ap-mcta ap-col">
            @php
            $applicants = [
            'text' => 'Applicant',
            'icon' => 'icon-trash text-danger',
            'attr' => "onclick=app.redirect('".route('students.index' , ['application'=>$application])."')",
            'class' => 'dropdown-item',
            'show'  => true,
            ];
            $buttons['buttons']['view'] = [
            'text' => 'View',
            'icon' => 'icon-eye',
            'attr' => "onclick=app.redirect('". ApplicationHelpers::getApplicationUrl($application, $school, $settings) ."',true)",
            'class' => 'dropdown-item',
            'show'  => PermissionHelpers::checkActionPermission('application', 'view', $application)

            ];
            $buttons['buttons']['edit'] = [
            'text' => 'Edit',
            'icon' => 'icon-note',
            'attr' => "onclick=app.redirect('".route('applications.edit' , $application)."')",
            'class' => 'dropdown-item',
            'show'  => PermissionHelpers::checkActionPermission('application', 'edit', $application)
            ];
            $buttons['buttons']['build'] = [
            'text' => 'Build',
            'icon' => 'fas fa-cubes',
            'attr' => "onclick=app.redirect('".route('applications.build' , $application)."')",
            'class' => 'dropdown-item',
            'show'  => PermissionHelpers::checkActionPermission('application', 'edit', $application)
            ];
            $buttons['buttons']['pdf-preview'] = [
                'text' => 'PDF Preview',
                'icon' => 'far fa-file-pdf',
                'attr' => "onclick=app.redirect('".route('application.pdf' , ['application' => $application, 'action' => 'view'])."',true)",
                'class' => '',
                'show'  => PermissionHelpers::checkActionPermission('application', 'view', $application)
            ];
            $buttons['buttons']['clone']= [
            'text' => 'Clone',
            'icon' => 'icon-layers',
            'attr' => "onclick=app.redirect('".route('application.cloning.create' , $application)."')",
            'class' => 'dropdown-item',
            'show'  => PermissionHelpers::checkActionPermission('application', 'create', $application)
            ];

            $buttons['buttons']['unpublish'] = [
            'text' => $application->published ?
            "<span data-application-id='$application->id'>Unpublish</span>" : "<span class='publish-button' data-application-id='$application->id'>Publish</span>",
            'icon' => 'icon-ban',
            'attr' => "onclick=app.toggleApplicationPublishStatus(this) data-application-id=$application->id" ,
            'class' => 'dropdown-item',
            'show'  => PermissionHelpers::checkActionPermission('application', 'edit', $application)
            ];

            $buttons['buttons']['delete'] = [
            'text' => 'Delete',
            'icon' => 'icon-trash text-danger',
            'attr' => 'onclick=app.deleteElement("'.route('applications.destroy' ,
            $application).'","","data-application-id")',
            'class' => 'dropdown-item',
            'show'  => PermissionHelpers::checkActionPermission('application', 'delete', $application)
            ];

            @endphp
            @include('back.layouts.core.helpers.table-actions-permissions' , $buttons)
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
