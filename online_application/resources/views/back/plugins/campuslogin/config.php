<?php
    return [
        'name' => 'CampusLogin',
        'type' => 'crm',
        'icon' => 'https://www1.campuslogin.com/images/ui/clui-cllogo-400.png',

        'fields' => [
            'url'               => 'required',
            'ORGID'             => 'required',
            'MailListID'        => 'required',
            'is_default'        => 'required',
        ],
        'secret_keys'           => []
    ];
