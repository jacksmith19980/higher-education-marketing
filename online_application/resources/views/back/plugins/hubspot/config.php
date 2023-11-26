<?php
    return [
        'name' => 'Hubspot',
        'type' => 'crm',
        'icon' => 'https://www.hubspot.com/hubfs/assets/hubspot.com/style-guide/brand-guidelines/guidelines_the-sprocket.svg',

        'fields' => [
            'api_key'           => 'required',
            'is_default'        => 'required',
        ],
        'secret_keys'           => ['api_key']
    ];
