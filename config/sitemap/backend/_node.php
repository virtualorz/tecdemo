<?php

use App\Classes\Sitemap\SitemapAccess;

return [
    '_prop' => [
        'permission' => SitemapAccess::LOGIN_REQUIRED,
        'login_group' => 'admin',
        'route' => [
            'group' => [
                'middleware' => ['permission', 'route_param_optional'],
                'namespace' => 'Backend',
            ],
            'method' => 'get',
            'attr' => [
                'uses' => 'IndexController@index',
            ],
        ],
    ],
    'web' => [],
    'member' => [],
    'login' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'LoginController@index',
                ],
            ],
        ],
        'submit' => [
            '_prop' => [
                'permission' => SitemapAccess::INHERIT,
                'route' => [
                    'method' => 'post',
                    'attr' => [
                        'uses' => 'LoginController@ajax_login',
                    ],
                ],
            ],
        ],
        'logout' => [
            '_prop' => [
                'permission' => SitemapAccess::INHERIT,
                'route' => [
                    'method' => 'post',
                    'attr' => [
                        'uses' => 'LoginController@ajax_logout',
                    ],
                ],
            ],
        ],
    ],
    'upload' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_REQUIRED,
            'route' => [
                'method' => 'post',
                'attr' => [
                    'uses' => 'UploadController@index',
                ],
            ],
        ],
        'delete' => [
            '_prop' => [
                'permission' => SitemapAccess::INHERIT,
                'route' => [
                    'method' => 'post',
                    'attr' => [
                        'uses' => 'UploadController@delete',
                    ],
                ],
            ],
        ],
    ],
];
