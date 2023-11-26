@foreach ($customFields as $customField)
    <div class="form-group row">
    @if($customField['field_type'] == 'list')
        <div class="col-12 ">
                @include('back.layouts.core.forms.select',
                    [
                    'name'          => $customField['slug'],
                    'label'         => '',
                    'placeholder'   => $customField['name'],
                    'class'         => '' ,
                    'required'      => (isset($customField['data']['mandatory']) && $customField['data']['mandatory'] == 1) ? true : false,
                    'attr'          => '',
                    'data'          => array_combine($customField['data']['values'] , $customField['data']['labels']),
                    'value'         => old($customField['slug']),
                    ])
        </div>
    @endif

    @if($customField['field_type'] == 'text')

        <div class="col-12 ">
                @include('back.layouts.core.forms.text-input',
                    [
                    'name'          => $customField['slug'],
                    'label'         => '',
                    'placeholder'   => $customField['name'],
                    'class'         => '' ,
                    'required'      => (isset($customField['data']['mandatory']) && $customField['data']['mandatory'] == 1) ? true : false,
                    'attr'          => '',
                    'value'         => old($customField['slug']),
                    ])
        </div>

    @endif
    </div>
@endforeach
