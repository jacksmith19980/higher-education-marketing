@include('back.layouts.core.forms.hidden-input',
[
    'name'      => 'type',
    'label'     => '' ,
    'class'     =>'ajax-form-field' ,
    'required'  => true,
    'attr'      => '',
    'value'     => $actionName
])

@include('back.layouts.core.forms.hidden-input',
[
    'name'      => 'application',
    'label'     => '' ,
    'class'     =>'ajax-form-field' ,
    'required'  => true,
    'attr'      => '',
    'value'     => $application->id
])

<div class="row">

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
        [
            'name'      => 'title',
            'label'     => 'Title' ,
            'class'     =>'ajax-form-field' ,
            'required'  => false,
            'attr'      => '',
            'value'     => isset($action->title) ? $action->title : __('Application Webhook') ,
        ])
    </div>

    <div class="col-md-12 new-field">
        @include('back.layouts.core.forms.text-input',
            [
            'name'      => 'properties[url]',
            'label'     => 'URL' ,
            'class'     =>'ajax-form-field' ,
            'required'  => true,
            'attr'      => '',
            'value'     => isset($action) ? $action->properties['url'] : ''
        ])
    </div>
    <div class="col-md-12 new-field">
            @include('back.layouts.core.forms.select',
                [
                    'name'      => 'properties[method]',
                    'label'     => 'HTTP Method' ,
                    'class'     => 'ajax-form-field' ,
                    'required'  => true,
                    'attr'      => '',
                    'data'      => [
                        'post'  => 'POST',
                        'get'   => 'GET'
                    ],
                    'value'     => isset($action) ? $action->properties['method'] : ''
    ])
    </div>
    <div class="col-md-12 new-field">

            @include('back.layouts.core.forms.list-repeater' , [
                'label'         => 'Headers',
                'data'          => isset($action) ? json_decode($action->properties['headers'] , true) : [],
                'name'          => 'properties[headers]',
                'class'         => 'ajax-form-field',
                'showImport'    => false,
                'placeholders'  => ['Key' , 'Value'],
            ])
    </div>
</div><!-- row -->
