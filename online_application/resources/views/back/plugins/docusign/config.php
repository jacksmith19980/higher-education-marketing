<?php

    return [
        'name' => 'DocuSign',
        'type' => 'signature',
        'icon' => 'https://go.docusign.com/media/img/Logo_DS_BK_360w.png',

        'fields' => [
            'client_id'         => 'required',
            'secret_key'        => 'required',
            'redirect_uri'      => 'required',
            'account_id'        => 'required',
            'folder_id'         => 'required',
            'brand_id'          => 'required',
        ],
        'secret_keys'           => ['secret_key','client_id']
    ];
