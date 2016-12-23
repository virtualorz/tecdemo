<?php

use App\Classes\DB\DBOperator;

return [
    'enable' => [
        0 => '停用',
        1 => '啟用'
    ],
    'sex' => [
        1 => '男',
        2 => '女'
    ],
    'admin-apply_mail' => [
        0 => '不收',
        1 => '要收'
    ],
    'admin_export-type' => [
        1 => '課程報名列表',
        2 => '課程統計資料',
        3 => '會員列表',
        4 => '索取紙本課程表',
        5 => '過期課程列表',
        6 => '課程資料分析',
    ],
    'course-duration_type' => [
        1 => '天',
        2 => '小時',
    ],
    'course-is_chosen' => [
        0 => '否',
        1 => '是',
    ],
    'course_class-class_way' => [
        1 => "課室授課",
        2 => "遠距教學(TP教室上課)",
        3 => "Virtual",
    ],
    'course_class-class_time' => [
        1 => "白天班",
        2 => "假日班",
        3 => "夜間班",
        9 => "夜間24班",
        10 => "夜間135班",
        4 => "包班",
        5 => "周六班",
        6 => "周日班",
        7 => "周五周六班",
        8 => "遠端學習班(英文授課)"
    ],
    'course_class-status' => [
        1 => "開課",
        2 => "延課",
        3 => "取消",
    ],
    'course_class-notice' => [
        0 => "未通知",
        1 => "已通知",
    ],
    'member-ispassport' => [
        0 => '非護照號碼',
        1 => '此為護照號碼',
    ],
    'member-order_epaper' => [
        0 => '否',
        1 => '是',
    ],
    'member-enable' => [
        0 => '未確認',
        1 => '已確認',
    ],
    'apply_item-pay_way' => [
        1 => '現金',
        2 => '信用卡',
        3 => '上課券',
        4 => '點券',
        5 => '重聽',
        6 => '未報價',
    ],
    'apply_item-pay_way_alt' => [
        1 => [
            0 => '現金',
            1 => '元整，請於上課前繳清',
        ],
        2 => '信用卡，請於上課前繳清',
        3 => '上課券',
        4 => '點券',
        5 => '重聽(限同版本)',
        6 => '尚未報價，請提供報價單',
    ],
    'apply_item-is_pay' => [
        0 => '未繳費',
        1 => '已繳費',
    ],
    'contact_ask_course_paper-is_join' => [
        0 => "無",
        1 => "有",
    ],
    'contact_ask_course_price-know_from' => [
        1 => "google搜尋",
        2 => "Facebook",
        3 => "其他搜尋引擎",
        4 => "eDM",
        5 => "同事介紹",
        6 => "朋友介紹",
        7 => "TP型錄",
        8 => "其他(請說明來源)"
    ],
    'contact_order_epaper-is_join' => [
        0 => "無",
        1 => "有",
    ],
    'aboutus_partner-aboutus_partner_cate_id' => [
        1 => "Technology Partners",
        2 => "Test Centers",
        3 => "Accreditation Centers",
    ],
    'check_out' => [
        1 => '匯款',
        2 => '信用卡'
    ],
    'pay_status' => [
        0 => '未付款',
        1 => '已付款'
    ],
    'attend_status' => [
        0 => '未出席',
        1 => '已出席'
    ],
    'is_pass' => [
        0 => '駁回',
        1 => '通過'
    ],
];
