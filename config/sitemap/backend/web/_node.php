<?php

use App\Classes\Sitemap\SitemapAccess;

return [
    '_prop' => [
        'permission' => SitemapAccess::ACCESS_REQUIRED,
        'menu' => true,
        'icon_class' => 'fa fa-desktop',
        'route' => [
            'method' => 'get',
            'attr' => [
                'uses' => 'WebController@index',
            ],
        ],
    ],
    'web' => [
        '_prop' => [
            'permission' => SitemapAccess::ACCESS_REQUIRED,
            'menu' => true,
            'icon_class' => 'fa fa-th',
        ],
        'indexmanage' => [
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
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'IndexSchoolController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'IndexSchoolController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'IndexSchoolController@ajax_add',
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
                'edit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'IndexSchoolController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'IndexSchoolController@ajax_edit',
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
                'detail' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'IndexSchoolController@detail',
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
                                'uses' => 'IndexSchoolController@ajax_delete',
                            ],
                        ],
                    ],
                ],
                'load_order' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'IndexSchoolController@ajax_load_order',
                            ],
                        ],
                    ],
                ],
                'set_order' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'IndexSchoolController@ajax_set_order',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'data' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-folder',
            ],
            'news' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-bullhorn',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'NewsController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'NewsController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'NewsController@ajax_add',
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
                                'uses' => 'NewsController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'NewsController@ajax_edit',
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
                                'uses' => 'NewsController@detail',
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
                                'uses' => 'NewsController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'tutor' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-calendar-o',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'TutorController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'TutorController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'TutorController@ajax_add',
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
                'edit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'TutorController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'TutorController@ajax_edit',
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
                'detail' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'TutorController@detail',
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
                                'uses' => 'TutorController@ajax_delete',
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
            'learning' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-share-square',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'LearningController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'LearningController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'LearningController@ajax_add',
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
                'edit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'LearningController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'LearningController@ajax_edit',
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
                'detail' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'LearningController@detail',
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
                                'uses' => 'LearningController@ajax_delete',
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
            'video' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-youtube-play',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'VideoController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'VideoController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'VideoController@ajax_add',
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
                                'uses' => 'VideoController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'VideoController@ajax_edit',
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
                                'uses' => 'VideoController@detail',
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
                                'uses' => 'VideoController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'plan' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-calendar',
            ],
            'plan' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-sitemap',
                    'route' => [
                        'method' => 'get',
                        'attr' => [
                            'uses' => 'PlanController@index',
                        ],
                    ],
                ],
                'submit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'PlanController@ajax_add',
                            ],
                        ],
                    ],
                ],
            ],
            'plantime' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-table',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'PlanTimeController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'PlanTimeController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'PlanTimeController@ajax_add',
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
                                'uses' => 'PlanTimeController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'PlanTimeController@ajax_edit',
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
                                'uses' => 'PlanTimeController@detail',
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
                                'uses' => 'PlanTimeController@ajax_delete',
                            ],
                        ],
                    ],
                ],
                'load_order' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'PlanTimeController@ajax_load_order',
                            ],
                        ],
                    ],
                ],
                'set_order' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'PlanTimeController@ajax_set_order',
                            ],
                        ],
                    ],
                ],
            ],
            'plantarget' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-hand-o-right',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'PlanTargetController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'PlanTargetController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'PlanTargetController@ajax_add',
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
                                'uses' => 'PlanTargetController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'PlanTargetController@ajax_edit',
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
                                'uses' => 'PlanTargetController@detail',
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
                                'uses' => 'PlanTargetController@ajax_delete',
                            ],
                        ],
                    ],
                ],
                'load_order' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'PlanTargetController@ajax_load_order',
                            ],
                        ],
                    ],
                ],
                'set_order' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'PlanTargetController@ajax_set_order',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'school' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-bar-chart-o',
            ],
            'schoolplan' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-calendar',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'SchoolPlanController@index',
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
                                'uses' => 'SchoolPlanController@detail',
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
                                'uses' => 'SchoolPlanController@ajax_delete',
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
            'schoolnews' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-bullhorn',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'SchoolNewsController@index',
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
                                'uses' => 'NewsController@detail',
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
                                'uses' => 'NewsController@ajax_delete',
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
            'schoolexecute' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-pencil-square-o',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'SchoolExecuteController@index',
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
                                'uses' => 'SchoolExecuteController@detail',
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
                                'uses' => 'SchoolExecuteController@ajax_delete',
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
    ],
];
