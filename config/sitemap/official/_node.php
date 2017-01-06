<?php

use App\Classes\Sitemap\SitemapAccess;

return [
    '_prop' => [
        'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
        'login_group' => 'member',
        'route' => [
            'group' => [
                'middleware' => ['permission','route_param_optional'],
                'namespace' => 'Official',
            ],
            'method' => 'get',
            'attr' => [
                'uses' => 'IndexController@index',
            ],
        ],
    ],
    'rddl_course_cert' => [
        '_prop' => [
            'permission' => SitemapAccess::INHERIT,
            'route' => [
                'method' => 'post',
                'attr' => [
                    'uses' => '\App\Http\Controllers\RelationDDLController@course_cert',
                ],
            ],
        ],
    ],
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
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                    'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'LoginController@logout',
                    ],
                ],
            ],
        ],
    ],
    'register' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'RegisterController@index',
                ],
            ],
        ],
        'submit' => [
            '_prop' => [
                'permission' => SitemapAccess::INHERIT,
                    'route' => [
                    'method' => 'post',
                    'attr' => [
                        'uses' => 'RegisterController@ajax_register',
                    ],
                ],
            ],
        ],
        'finish' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'RegisterController@finish',
                    ],
                ],
            ],
        ],
        'get_department' => [
            '_prop' => [
                'permission' => SitemapAccess::INHERIT,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'RegisterController@ajax_get_department',
                    ],
                ],
            ],
        ],
        'get_pi' => [
            '_prop' => [
                'permission' => SitemapAccess::INHERIT,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'RegisterController@ajax_get_pi',
                    ],
                ],
            ],
        ],
    ],
    'forget_pw' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'ForgetpwController@index',
                ],
            ],
        ],
        'submit' => [
            '_prop' => [
                'permission' => SitemapAccess::INHERIT,
                    'route' => [
                    'method' => 'post',
                    'attr' => [
                        'uses' => 'ForgetpwController@ajax_send',
                    ],
                ],
            ],
        ],
        'finish' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'ForgetpwController@finish',
                    ],
                ],
            ],
            'submit' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                        'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'ForgetpwController@ajax_send',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'reset_pw' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'ResetpwController@index',
                ],
            ],
        ],
        'submit' => [
            '_prop' => [
                'permission' => SitemapAccess::INHERIT,
                    'route' => [
                    'method' => 'post',
                    'attr' => [
                        'uses' => 'ResetpwController@ajax_set',
                    ],
                ],
            ],
        ],
        'finish' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'ResetpwController@finish',
                    ],
                ],
            ],
        ],
    ],
    'activity' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'ActivityController@index',
                ],
            ],
        ],
        'reservation' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'param' => '{id}',
                    'attr' => [
                        'uses' => 'ActivityController@reservation',
                    ],
                ],
            ],
            'submit' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                        'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'ActivityController@ajax_reservation',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'news' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'param' => '{id}',
                'attr' => [
                    'uses' => 'NewsController@index',
                ],
            ],
        ],
    ],
    'instrument' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'InstrumentController@index',
                ],
            ],
        ],
        'reservation' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'param' => '{id}',
                    'attr' => [
                        'uses' => 'InstrumentController@reservation',
                    ],
                ],
             ],
             'submit' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                        'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'InstrumentController@ajax_reservation',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'contact_us' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'ContactController@index',
                ],
            ],
        ],
    ],
    'member' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'MemberController@index',
                ],
            ],
        ],
        'basic' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberController@basic',
                    ],
                ],
            ],
            'submit' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                        'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'MemberController@ajax_edit',
                        ],
                    ],
                ],
            ],
        ],
        'journal' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberJournalController@index',
                    ],
                ],
            ],
            'add' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'attr' => [
                            'uses' => 'MemberJournalController@add',
                        ],
                    ],
                 ],
                'submit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'MemberJournalController@ajax_add',
                            ],
                        ],
                    ],
                ],
            ],
            'detail' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'param' => '{id}',
                        'attr' => [
                            'uses' => 'MemberJournalController@detail',
                        ],
                    ],
                ],
            ],
            'delete' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                    'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'MemberJournalController@ajax_delete',
                        ],
                    ],
                ],
            ],
        ],
        'e_portfolio' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberEportfolioController@index',
                    ],
                ],
            ],
        ],
        'message' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberMessageController@index',
                    ],
                ],
            ],
            'detail' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'param' => '{id}',
                        'attr' => [
                            'uses' => 'MemberMessageController@detail',
                        ],
                    ],
                ],
            ],
        ],
        'activity' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberActivityController@index',
                    ],
                ],
            ],
            'reg' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'attr' => [
                            'uses' => 'MemberActivityController@reg',
                        ],
                    ],
                 ],
                'submit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'MemberActivityController@ajax_reg',
                            ],
                        ],
                    ],
                ],
            ],
            'cancel' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                    'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'MemberActivityController@ajax_cancel',
                        ],
                    ],
                ],
            ],
        ],
        'instrument' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberInstrumentController@index',
                    ],
                ],
            ],
            'cancel' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                    'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'MemberInstrumentController@ajax_cancel',
                        ],
                    ],
                ],
            ],
        ],
        'bill' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberBillController@index',
                    ],
                ],
            ],
            'detail' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'attr' => [
                            'uses' => 'MemberBillController@detail',
                        ],
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
