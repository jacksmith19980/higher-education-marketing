<div class="input-group-append actions-button">

        <input type="hidden" readonly id="application_url" value="{{route($application->route,['school'=> $school,'application'=>$application])}}" />

        @php
            $buttons['copy'] = [
                    'text' => 'Copy Link',
                    'icon' => 'icon-docs ',
                    'attr' => "onclick=app.copyToClipBoard('application_url')",
                    'class' => '',
            ];
            $buttons['preview'] = [
                    'text'  => 'Preview',
                    'icon'  => 'icon-screen-desktop',
                    'href'  => route('application.preview' , ['school'=> $school , 'application' => $application]),
                    'attr'  => "target=_blank",
                    'class' => 'view-application',
            ];
            $buttons['view'] = [
                    'text'  => 'View',
                    'icon'  => 'icon-eye',
                    'href'  => route($application->route , ['school'=> $school ,
                    'application' => $application]),
                    'attr'  => "target=_blank",
                    'class' => 'view-application',
            ];
            $buttons['edit'] = [
                    'text'  => 'Edit',
                    'icon'  => 'icon-note',
                    "href"  => route('applications.edit' , $application),
                    'attr'  => "",
                    'class' => '',
            ];

            if(!$builder){
                $buttons['build'] = [
                    'text'      => 'Build',
                    'icon'      => 'fas fa-cubes',
                    "href"      => route('applications.build' , $application),
                    'attr'      => "",
                    'class'     => 'dropdown-item',
                    'show'      => PermissionHelpers::checkActionPermission('application', 'edit', $application)
                ];
                $buttons['clone']= [
                    'text'      => 'Clone',
                    'icon'      => 'icon-note',
                    'attr'      => '',
                    'href'      => route('application.cloning.create' , $application),
                    'class'     => 'dropdown-item',
                    'show'      => PermissionHelpers::checkActionPermission('application', 'create', $application)
                ];
            }
            $buttons['unpublish'] = [
                'text' => $application->published ?
                "<span data-application-id='$application->id'>Unpublish</span>" : "<span class='publish-button' data-application-id='$application->id'>Publish</span>",
                'icon' => 'icon-ban',
                'attr' => "onclick=app.toggleApplicationPublishStatus(this) data-application-id=$application->id" ,
                'class' => 'dropdown-item',
                'show'  => PermissionHelpers::checkActionPermission('application', 'edit', $application)
            ];

            $buttons['delete'] = [
                    'text' => 'Delete',
                    'icon' => 'icon-trash text-danger',
                    'attr' => 'onclick=app.deleteElement("'.route('applications.destroy' , ['application' => $application , 'redirect' => true]
                    ).'","","data-application-id")',
                    'class' => '',
            ];

        @endphp

        @include('back.layouts.core.helpers.table-actions' , [
        'show_check_box' => false,
        'buttons' => $buttons,
            ]
        )
</div>
