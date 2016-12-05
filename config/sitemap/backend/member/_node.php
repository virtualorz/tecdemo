<?php

use App\Classes\Sitemap\SitemapAccess;

return [
    '_prop' => [
        'permission' => SitemapAccess::ACCESS_REQUIRED,
        'menu' => true,
        'icon_class' => 'fa fa-group',
        'route' => [
            'method' => 'get',
            'attr' => [
                'uses' => 'MemberController@index',
            ],
        ],
    ],
    'member' => [
        '_prop' => [
            'permission' => SitemapAccess::ACCESS_REQUIRED,
            'menu' => true,
            'icon_class' => 'fa fa-th',
        ],
        'memberdata' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-list-ul',
            ],
            'protofolio' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-list-alt',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'MemberProtofolioController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'MemberProtofolioController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'MemberProtofolioController@ajax_add',
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
                                    'uses' => 'MemberProtofolioController@ajax_get_department',
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
                                    'uses' => 'MemberProtofolioController@ajax_get_pi',
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
                                'uses' => 'MemberProtofolioController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'MemberProtofolioController@ajax_edit',
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
                                    'uses' => 'MemberProtofolioController@ajax_get_department',
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
                                    'uses' => 'MemberProtofolioController@ajax_get_pi',
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
                                'uses' => 'MemberProtofolioController@detail',
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
                                'uses' => 'MemberProtofolioController@ajax_delete',
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
                                'uses' => 'MemberProtofolioController@ajax_get_department',
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
                                'uses' => 'MemberProtofolioController@ajax_get_pi',
                            ],
                        ],
                    ],
                ],
                'active' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'MemberProtofolioController@active',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'MemberProtofolioController@ajax_active',
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
                                    'uses' => 'MemberProtofolioController@ajax_get_department',
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
                                    'uses' => 'MemberprotofolioController@ajax_get_pi',
                                ],
                            ],
                        ],
                    ],
                ],
                'notice' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'MemberProtofolioController@notice',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'MemberProtofolioController@ajax_notice',
                                ],
                            ],
                        ],
                    ],
                ],
                'reservationlog' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'MemberProtofolioController@reservationlog',
                            ],
                        ],
                    ],
                ],
            ],
            'error' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-list-alt',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'MemberErrorController@index',
                        ],
                    ],
                ],
                'notice' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'MemberErrorController@ajax_notice',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'admin' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-user',
            ],
            'permission' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-tasks',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'AdminPermissionController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'AdminPermissionController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'AdminPermissionController@ajax_add',
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
                                'uses' => 'AdminPermissionController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'AdminPermissionController@ajax_edit',
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
                                'uses' => 'AdminPermissionController@detail',
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
                                'uses' => 'AdminPermissionController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'admin' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-users',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'AdminController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'AdminController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'AdminController@ajax_add',
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
                                'uses' => 'AdminController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'AdminController@ajax_edit',
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
                                'uses' => 'AdminController@detail',
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
                                'uses' => 'AdminController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'adminlog' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-users',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'AdminLogController@index',
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
                                'uses' => 'AdminLogController@detail',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
