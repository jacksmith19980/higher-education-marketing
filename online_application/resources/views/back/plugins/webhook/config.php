<?php
    return [
        'name' => 'Webhook',
        'type' => 'crm',
        'icon' => 'https://cdn.worldvectorlogo.com/logos/webhooks.svg',

        'fields' => [
            'webhook_url'           => 'required',
            'webhook_method'        => 'required',
            'is_default'            => 'required',
        ],
        'secret_keys'           => []
    ];
