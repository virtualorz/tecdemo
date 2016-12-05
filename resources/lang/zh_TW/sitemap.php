<?php

return [
    'backend' => ['_name' => '首頁',
        'login' => ['_name' => '登入',
            'logout' => ['_name' => '登出',],],
        'web' => ['_name' => '資料管理',
            'web' =>['_name' => '資料管理',
                'system' => ['_name' => '系統資料管理',
                    'indexannounce' => ['_name' => '首頁公告',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                    'tcdata' => ['_name' => '技術人員資料',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                    'pilist' => ['_name' => '指導教授列表',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                ],
                'activity' => ['_name' => '活動資料管理',
                    'activitytype' => ['_name' => '活動類型',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                    'activitylist' => ['_name' => '活動列表',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                        'reservation' => ['_name' => '預約資料',],
                        'attend' => ['_name' => '報導',],
                    ],
                    'activitypass' => ['_name' => '活動通過審核',
                        'list' => ['_name' => '學生名單',],
                    ],
                    'activityreg' => ['_name' => '補登記資料管理',
                        'detail' => ['_name' => '內容',],
                    ],
                ],
                'instrument' => ['_name' => '儀器資料管理',
                    'site' => ['_name' => '場地設定',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                    'section' => ['_name' => '使用時段設定',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                    'vacation' => ['_name' => '排休設定',
                        
                    ],
                    'instrumenttype' => ['_name' => '儀器類型設定',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                    'instrument' => ['_name' => '儀器資料設定',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                        'vacation' => ['_name' => '排休',],
                        'rate' => ['_name' => '費率表',],
                    ],
                    'reservation' => ['_name' => '預約資料管理',
                        'complete' => ['_name' => '完成',],
                    ],
                    'payment' => ['_name' => '繳費資料查詢',
                        'complete' => ['_name' => '繳費完成',],
                        'confirm' => ['_name' => '確認帳單',],
                        'reminder' => ['_name' => '催繳',],
                    ],
                ],
            ]
        ],
        'member' => ['_name' => '會員管理',
            'member' => ['_name' => '會員管理',
                'memberdata' => ['_name' => '護照資料管理',
                    'protofolio' => ['_name' => '護照列表',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                        'active' => ['_name' => '開通',],
                        'notice' => ['_name' => '通知訊息',],
                        'activitylog' => ['_name' => '活動紀錄',],
                    ],
                    'error' => ['_name' => '異常提醒',
                    ],
                ],
                'admin' => ['_name' => '後台管理員',
                    'permission' => ['_name' => '管理員權限',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                    'admin' => ['_name' => '管理員名單',
                        'add' => ['_name' => '新增',],
                        'edit' => ['_name' => '編輯',],
                        'detail' => ['_name' => '內容',],
                    ],
                    'adminlog' => ['_name' => '管理員異常紀錄',
                        'detail' => ['_name' => '內容',],
                    ],
                ],
            ],
        ],
    ],
    'official' => ['_name' => '首頁',
        'login' => ['_name' => '登入',
            'logout' => ['_name' => '登出',],],
        'forgetpw' => ['_name' => '忘記密碼',
            'check' => ['_name' => 'Email驗證',],
            'reset' => ['_name' => '重設密碼',],],
        'register' => ['_name' => '註冊',
            'check' => ['_name' => 'Email驗證',],
            'complete' => ['_name' => '驗證完成',],],
        'basicdata' => ['_name' => '基本資料',],
        'activitymanage' => ['_name' => '活動管理',
            'add' => ['_name' => '新增',],
            'edit' => ['_name' => '編輯',],
            'student' => ['_name' => '學生管理',],
            'groupdata' => ['_name' => '資料管理',
                'data' => ['_name' => '組別資料',
                    'result' => ['_name' => '查看結果',],
                ],],
            ],
        'activity' => ['_name' => '活動進行',
            'result' => ['_name' => '填寫總結',],],
    ],
];
