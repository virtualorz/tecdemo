<?php

return [
    'apply' => [
        'fk_restrict' => [
            'apply_item' => 'apply_id',
        ],
    ],
    'course_partner' => [
        'fk_restrict' => [
            'course_cert' => 'course_partner_id',
        ],
    ],
    'course_cert' => [
        'fk_restrict' => [
            'course' => 'course_cert_id',
        ],
    ],
    'course' => [
        'fk_restrict' => [
            'course_class' => 'course_id',
        ],
    ],
    'course_class' => [
        'fk_restrict' => [
            'course_class' => 'delay_course_class_id',
            'course_class_suspend' => 'course_class_id',
            'apply_item' => 'first_course_class_id',
            'apply_item' => 'last_course_class_id',
        ],
    ],
    'course_class_locale' => [
        'fk_restrict' => [
            'course_class' => 'course_class_locale_id',
        ],
    ],
    'member' => [
        'fk_restrict' => [
            'apply_item' => 'member_id',
        ],
    ],
];
