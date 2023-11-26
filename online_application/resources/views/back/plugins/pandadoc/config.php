<?php
    return [
        'name' => 'Pandadoc',
        'type' => 'signature',
        'icon' => 'https://avatars.githubusercontent.com/u/9800799?s=280&v=4',

        'fields' => [
            'base_url'          => 'required',
            /* 'client_id'         => 'required',
            'application_id'    => 'required', */
            'api_key'           => 'required',
           /*  'redirect_uri'      => 'required', */
        ],
        'secret_keys'           => ['api_key']
    ];
