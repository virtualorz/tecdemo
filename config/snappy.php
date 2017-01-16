<?php

return array(
    'pdf' => array(
        'enabled' => true,
        'binary' => '"' . base_path('vendor/vendor/wkhtmltopdf/'.env('OS').'-' . env('OS_BIT') . '/bin/wkhtmltopdf') . '"',
        'timeout' => false,
        'options' => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary' => '"' . base_path('vendor/vendor/wkhtmltopdf/'.env('OS').'-' . env('OS_BIT') . '/bin/wkhtmltoimage') . '"',
        'timeout' => false,
        'options' => array(),
    ),
);
