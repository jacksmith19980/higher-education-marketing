<?php
    return [
        'name' => 'AdobeSign',
        'type' => 'signature',
        'icon' => 'https://secure.na3.echocdn.com/images/doc-cloud/rb_adobesign_webheader_1x.2.png',

        'fields' => [
            'base_url'          => 'required',
            'group_id'          => 'required',
            'client_id'         => 'required',
            'application_id'    => 'required',
            'client_secret'     => 'required',
            'redirect_uri'      => 'required',
        ],
        'secret_keys'           => ['client_secret']
    ];
