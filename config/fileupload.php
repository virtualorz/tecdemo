<?php

return [
    'root_url' => 'files', //url path
    'root_dir' => 'files', //file system path
    'dir' => [
        'files',
        'news',
        'school_photo',
        'school_plan',
        'school_execute',
        'tutor_file',
        'tutor_photo',
        'learnign_file',
        'learnign_photo',
        'execute_file',
        'execute_photo',
    ],
    'ext' => 'jpg|jpeg|png|gif|doc|docx|xls|xlsx|pdf|zip|rar|7z',
    'size' => 10 * (1024 * 1024),// byte
    'px' => '360_270',
    'thumb' => array(
        'width' => 150,
        'height' => 150
    ),
    'tinify_key' => env('TINIFY_KEY', 'W3tdp1uE4H-5yRq3R7wHha1c7ikH-HsT'),
];
