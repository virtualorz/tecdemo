<?php

return [
    //
    'pwd_enc_pre' => 'training',
    'pwd_enc_post' => 'partners',
    
    //
    'group' => [
        'member' => [
            'super' => [
                'id' => 0,
                'account' => 'manager',
                'password' => 'c47f409ddf31d279a67d4c4e4aecd7ad',
                'name' => 'Super Admin',
                'title' => 'Super Admin',
            ],
            'login' => 'official.login',
            'logout' => 'official.login.logout',
        ],
        'admin' => [
            'super' => [
                'id' => 0,
                'account' => 'manager',
                'password' => 'c47f409ddf31d279a67d4c4e4aecd7ad',
                'name' => 'Super Admin',
                'title' => 'Super Admin',
            ],
            'login' => 'backend.login',
            'logout' => 'backend.login.logout',
        ]
    ]
];
