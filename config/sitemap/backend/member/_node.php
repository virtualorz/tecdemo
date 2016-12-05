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
        'school' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-list-ul',
            ],
            'list' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-list-alt',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'SchoolListController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'SchoolListController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'SchoolListController@ajax_add',
                                ],
                            ],
                        ],
                    ],
                    'get_town' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'get',
                                'attr' => [
                                    'uses' => 'SchoolListController@ajax_get_town',
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
                                'uses' => 'SchoolListController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'SchoolListController@ajax_edit',
                                ],
                            ],
                        ],
                    ],
                    'get_town' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'get',
                                'attr' => [
                                    'uses' => 'SchoolListController@ajax_get_town',
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
                                'uses' => 'SchoolListController@detail',
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
                                'uses' => 'SchoolListController@ajax_delete',
                            ],
                        ],
                    ],
                ],
                'get_town' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'SchoolListController@ajax_get_town',
                            ],
                        ],
                    ],
                ],
                'get_school' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'IndexSchoolController@ajax_get_school',
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
        ],
    ],
];
