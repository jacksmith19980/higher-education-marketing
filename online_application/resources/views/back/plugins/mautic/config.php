<?php
    return [
        'name' => 'Mautic',
        'type' => 'crm',
        'icon' => 'https://www.mautic.org/sites/default/files/content-images/Mautic_Logo_Vertical_RGB_LB_19.png',

        'fields' => [
            'username'           => 'required',
            'password'           => 'required',
            'base_url'           => 'required',
            'is_default'        => 'required',
        ],
        'secret_keys'           => ['password']
    ];
