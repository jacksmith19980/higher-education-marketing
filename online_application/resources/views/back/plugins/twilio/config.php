<?php

    return [
        'name' => 'Twilio',
        'icon' => 'https://cdn.freebiesupply.com/logos/large/2x/twilio-logo-png-transparent.png',

        'fields' => [
            'account_sid' => 'required',
            'auth_token'=> 'required',
            'call_from'=> 'required',
        ],
    ];
