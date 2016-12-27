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
        'system' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-th',
            ],
            'indexannounce' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-list-ul',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'IndexAnnounceController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'IndexAnnounceController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'IndexAnnounceController@ajax_add',
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
                                'uses' => 'IndexAnnounceController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'IndexAnnounceController@ajax_edit',
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
                                'uses' => 'IndexAnnounceController@detail',
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
                                'uses' => 'IndexAnnounceController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'tcdata' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-male',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'TCDataController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'TCDataController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'TCDataController@ajax_add',
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
                                'uses' => 'TCDataController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'TCDataController@ajax_edit',
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
                                'uses' => 'TCDataController@detail',
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
                                'uses' => 'TCDataController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'pilist' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-users',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'PIListController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'PIListController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'PIListController@ajax_add',
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
                                    'uses' => 'PIListController@ajax_get_department',
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
                                'uses' => 'PIListController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'PIListController@ajax_edit',
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
                                    'uses' => 'PIListController@ajax_get_department',
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
                                'uses' => 'PIListController@detail',
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
                                'uses' => 'PIListController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'activity' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-folder',
            ],
            'activitytype' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-plus-square-o',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'ActivityTypeController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'ActivityTypeController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityTypeController@ajax_add',
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
                                'uses' => 'ActivityTypeController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityTypeController@ajax_edit',
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
                                'uses' => 'ActivityTypeController@detail',
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
                                'uses' => 'ActivityTypeController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'activitylist' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-calendar-o',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'ActivityListController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'ActivityListController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityListController@ajax_add',
                                ],
                            ],
                        ],
                    ],
                    'get_instrument' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'get',
                                'attr' => [
                                    'uses' => 'ActivityListController@ajax_get_instrument',
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
                                'uses' => 'ActivityListController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityListController@ajax_edit',
                                ],
                            ],
                        ],
                    ],
                    'get_instrument' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'get',
                                'attr' => [
                                    'uses' => 'ActivityListController@ajax_get_instrument',
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
                                'uses' => 'ActivityListController@detail',
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
                                'uses' => 'ActivityListController@ajax_delete',
                            ],
                        ],
                    ],
                ],
                'reservation' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'ActivityReservationController@index',
                            ],
                        ],
                    ],
                    'cancel' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityReservationController@ajax_cancel',
                                ],
                            ],
                        ],
                    ],
                ],
                'attend' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'ActivityAttendController@index',
                            ],
                        ],
                    ],
                    'attend' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityAttendController@ajax_attend',
                                ],
                            ],
                        ],
                    ],
                    'attend_cancel' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityAttendController@ajax_attend_cancel',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'activitypass' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-share-square',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'ActivityPassController@index',
                        ],
                    ],
                ],
                'list' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'ActivityPassController@student_list',
                            ],
                        ],
                    ],
                    'pass' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityPassController@ajax_pass',
                                ],
                            ],
                        ],
                    ],
                    'pass_cancel' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityPassController@ajax_pass_cancel',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'activityreg' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-rotate-right',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'ActivityRegController@index',
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
                                'uses' => 'ActivityRegController@detail',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'ActivityRegController@ajax_edit',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'instrument' => [
            '_prop' => [
                'permission' => SitemapAccess::ACCESS_REQUIRED,
                'menu' => true,
                'icon_class' => 'fa fa-camera',
            ],
            'site' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-credit-card',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'InstrumentSiteController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'InstrumentSiteController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentSiteController@ajax_add',
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
                                'uses' => 'InstrumentSiteController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentSiteController@ajax_edit',
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
                                'uses' => 'InstrumentSiteController@detail',
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
                                'uses' => 'InstrumentSiteController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'section' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'glyphicon glyphicon-time',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'InstrumentSectionController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'InstrumentSectionController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentSectionController@ajax_add',
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
                                'uses' => 'InstrumentSectionController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentSectionController@ajax_edit',
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
                                'uses' => 'InstrumentSectionController@detail',
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
                                'uses' => 'InstrumentSectionController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'supplies' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-eraser',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'InstrumentSuppliesController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'InstrumentSuppliesController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentSuppliesController@ajax_add',
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
                                'uses' => 'InstrumentSuppliesController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentSuppliesController@ajax_edit',
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
                                'uses' => 'InstrumentSuppliesController@detail',
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
                                'uses' => 'InstrumentSuppliesController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'vacation' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-calendar',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'InstrumentAllVacationController@index',
                        ],
                    ],
                ],
                'submit' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'InstrumentAllVacationController@ajax_edit',
                            ],
                        ],
                    ],
                ],
            ],
            'instrumenttype' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-book',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'InstrumentTypeController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'InstrumentTypeController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentTypeController@ajax_add',
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
                                'uses' => 'InstrumentTypeController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentTypeController@ajax_edit',
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
                                'uses' => 'InstrumentTypeController@detail',
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
                                'uses' => 'InstrumentTypeController@ajax_delete',
                            ],
                        ],
                    ],
                ],
            ],
            'instrument' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-search-minus',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'InstrumentController@index',
                        ],
                    ],
                ],
                'add' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'attr' => [
                                'uses' => 'InstrumentController@add',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentController@ajax_add',
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
                                'uses' => 'InstrumentController@edit',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentController@ajax_edit',
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
                                'uses' => 'InstrumentController@detail',
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
                                'uses' => 'InstrumentController@ajax_delete',
                            ],
                        ],
                    ],
                ],
                'vacation' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'icon_class' => 'fa fa-hand-o-right',
                        'route' => [
                            'method' => 'get',
                            'param' => '{optional?}',
                            'attr' => [
                                'uses' => 'InstrumentVacationController@index',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentVacationController@ajax_edit',
                                ],
                            ],
                        ],
                    ],
                ],
                'rate' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'icon_class' => 'fa fa-hand-o-right',
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'InstrumentRateController@index',
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
                                    'uses' => 'InstrumentRateController@edit',
                                ],
                            ],
                        ],
                        'submit' => [
                            '_prop' => [
                                'permission' => SitemapAccess::INHERIT,
                                'route' => [
                                    'method' => 'post',
                                    'attr' => [
                                        'uses' => 'InstrumentRateController@ajax_edit',
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
                                    'uses' => 'InstrumentRateController@detail',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'reservation' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-cogs',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'InstrumentReservationController@index',
                        ],
                    ],
                ],
                'complete' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'InstrumentReservationController@complete',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentReservationController@ajax_complete',
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
                                'uses' => 'InstrumentReservationController@ajax_delete',
                            ],
                        ],
                    ],
                ],
                'dcomplete' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'InstrumentReservationController@ajax_complete',
                            ],
                        ],
                    ],
                ],
                'notattend' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'InstrumentReservationController@ajax_notattend',
                            ],
                        ],
                    ],
                ],
                'removewait' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'InstrumentReservationController@ajax_removewait',
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
                                'uses' => 'InstrumentReservationController@detail',
                            ],
                        ],
                    ],
                ],
            ],
            'payment' => [
                '_prop' => [
                    'permission' => SitemapAccess::ACCESS_REQUIRED,
                    'menu' => true,
                    'icon_class' => 'fa fa-cny',
                    'route' => [
                        'method' => 'get',
                        'param' => '{optional?}',
                        'attr' => [
                            'uses' => 'InstrumentPaymentController@index',
                        ],
                    ],
                ],
                'complete_pay' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'InstrumentPaymentController@complete',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentPaymentController@ajax_complete',
                                ],
                            ],
                        ],
                    ],
                ],
                'confirm_pay' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'InstrumentPaymentController@confirm',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentPaymentController@ajax_confirm',
                                ],
                            ],
                        ],
                    ],
                ],
                'reminder_pay' => [
                    '_prop' => [
                        'permission' => SitemapAccess::ACCESS_REQUIRED,
                        'route' => [
                            'method' => 'get',
                            'param' => '{id}',
                            'attr' => [
                                'uses' => 'InstrumentPaymentController@reminder',
                            ],
                        ],
                    ],
                    'submit' => [
                        '_prop' => [
                            'permission' => SitemapAccess::INHERIT,
                            'route' => [
                                'method' => 'post',
                                'attr' => [
                                    'uses' => 'InstrumentPaymentController@ajax_reminder',
                                ],
                            ],
                        ],
                    ],
                ],
                'output' => [
                    '_prop' => [
                        'permission' => SitemapAccess::INHERIT,
                        'route' => [
                            'method' => 'post',
                            'attr' => [
                                'uses' => 'InstrumentReservationController@ajax_output',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
