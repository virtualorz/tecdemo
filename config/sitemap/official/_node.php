<?php

use App\Classes\Sitemap\SitemapAccess;

return [
    '_prop' => [
        'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
        'login_group' => 'member',
        'route' => [
            'group' => [
                'middleware' => ['route_param_optional'],
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
    'school' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'SchoolController@index',
                ],
            ],
        ],
        'plan' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'param' => '{id}',
                    'attr' => [
                        'uses' => 'SchoolController@plan',
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
                        'uses' => 'SchoolController@news',
                    ],
                ],
            ],
            'content' => [
                '_prop' => [
                    'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'param' => '{id}',
                        'attr' => [
                            'uses' => 'SchoolController@news_content',
                        ],
                    ],
                ],
            ],
        ],
        'execute' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'param' => '{id}',
                    'attr' => [
                        'uses' => 'SchoolController@execute',
                    ],
                ],
            ],
            'content' => [
                '_prop' => [
                    'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'param' => '{id}',
                        'attr' => [
                            'uses' => 'SchoolController@execute_content',
                        ],
                    ],
                ],
            ],
        ],
        'tutor' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'param' => '{id}',
                    'attr' => [
                        'uses' => 'SchoolController@tutor',
                    ],
                ],
            ],
            'content' => [
                '_prop' => [
                    'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'param' => '{id}',
                        'attr' => [
                            'uses' => 'SchoolController@tutor_content',
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
                'attr' => [
                    'uses' => 'NewsController@index',
                ],
            ],
        ],
        'content' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'param' => '{id}',
                    'attr' => [
                        'uses' => 'NewsController@content',
                    ],
                ],
             ],
        ],
    ],
    'plan' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'PlanController@index',
                ],
            ],
        ],
        'time' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'PlanController@time',
                    ],
                ],
             ],
        ],
        'target' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'PlanController@target',
                    ],
                ],
             ],
        ],
    ],
    'tutor' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'TutorController@index',
                ],
            ],
        ],
    ],
    'learning' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'LearningController@index',
                ],
            ],
        ],
        'content' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'param' => '{id}',
                    'attr' => [
                        'uses' => 'LearningController@content',
                    ],
                ],
             ],
        ],
    ],
    'video' => [
        '_prop' => [
            'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
            'route' => [
                'method' => 'get',
                'attr' => [
                    'uses' => 'VideoController@index',
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
        'plan' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberPlanController@index',
                    ],
                ],
            ],
            'submit' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                        'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'MemberPlanController@ajax_edit',
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
                    'attr' => [
                        'uses' => 'MemberNewsController@index',
                    ],
                ],
            ],
            'add' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'attr' => [
                            'uses' => 'MemberNewsController@add',
                        ],
                    ],
                 ],
                'submit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'MemberNewsController@ajax_add',
                            ],
                        ],
                    ],
                ],
            ],
            'edit' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'param' => '{id}',
                        'attr' => [
                            'uses' => 'MemberNewsController@edit',
                        ],
                    ],
                ],
                'submit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'MemberNewsController@ajax_edit',
                            ],
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
                            'uses' => 'MemberNewsController@ajax_delete',
                        ],
                    ],
                ],
            ],
        ],
        'execute' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'MemberExecuteController@index',
                    ],
                ],
            ],
            'add' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'attr' => [
                            'uses' => 'MemberExecuteController@add',
                        ],
                    ],
                 ],
                'submit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'MemberExecuteController@ajax_add',
                            ],
                        ],
                    ],
                ],
            ],
            'edit' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'route' => [
                        'method' => 'get',
                        'param' => '{id}',
                        'attr' => [
                            'uses' => 'MemberExecuteController@edit',
                        ],
                    ],
                ],
                'submit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'MemberExecuteController@ajax_edit',
                            ],
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
                            'uses' => 'MemberExecuteController@ajax_delete',
                        ],
                    ],
                ],
            ],
        ],
        'changepw' => [
            '_prop' => [
                'permission' => SitemapAccess::LOGIN_NOT_REQUIRED,
                'route' => [
                    'method' => 'get',
                    'attr' => [
                        'uses' => 'ChangepwController@index',
                    ],
                ],
            ],
            'submit' => [
                '_prop' => [
                    'permission' => SitemapAccess::INHERIT,
                    'route' => [
                        'method' => 'post',
                        'attr' => [
                            'uses' => 'ChangepwController@ajax_edit',
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
