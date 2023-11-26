<div class="col-md-6 col-md-offset-3 offset-3">
    @include('back.layouts.core.forms.select',
    [
        'name'          => 'application_actions',
        'label'         => 'Actions' ,
        'class'         =>'' ,
        'value'         => '',
        'required'      => false,
        'attr'          => 'onchange=app.getApplicationActionDetails(this)',
        'data'          => ApplicationHelpers::getDefaultActions()
    ])
</div>

<div class="col-md-12">
    <div id="accordion_application_action" role="tablist" aria-multiselectable="true" class="row applicationActionDetails accordion">
                @if( isset($actions) )

                    @foreach($actions as $action)

                         @include('back.applications.actions.'.$action->action , ['action' => $action ] )

                    @endforeach

                @endif

    </div>
</div>
