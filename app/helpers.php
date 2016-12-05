<?php

use Illuminate\Support\Facades\Config;

function str_empty_to_null($str) {
    return ($str === '') ? null : $str;
}

function str_password($len = 8) {
    $rt = [];

    // remove o,O,0,1,l
    $word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
    $word_sp = '!@#$%&?:.,+-*/';

    $len_word = strlen($word);
    $len_word_sp = strlen($word_sp);
    $len_sp = intval(sqrt($len));

    for ($i = 0; $i < $len; $i++) {
        $rt[] = $word[rand() % $len_word];
    }

    $rand_keys = array_rand($rt, $len_sp);
    foreach ($rand_keys as $k => $v) {
        $rt[$v] = $word_sp[rand() % $len_word_sp];
    }

    return implode('', $rt);
}

function gcd($a, $b) {
    if ($a == 0 || $b == 0) {
        return abs(max(abs($a), abs($b)));
    }
    $r = $a % $b;
    return ($r != 0) ? gcd($b, $r) : abs($b);
}

function filesize_convert_unit($size) {
    $sizeUnit = '0 byte';
    $size = intval($size);
    if ($size >= 1073741824) {
        $sizeUnit = (round($size / 1073741824 * 100) / 100) . ' GB';
    } else if ($size >= 1048576) {
        $sizeUnit = (round($size / 1048576 * 100) / 100) . ' MB';
    } else if ($size >= 1024) {
        $sizeUnit = (round($size / 1024 * 100) / 100) . ' KB';
    } else if ($size > 1) {
        $sizeUnit = $size + ' bytes';
    } else {
        $sizeUnit = $size + ' byte';
    }

    return $sizeUnit;
}

function datetime_format($datetime, $format) {
    if (empty($datetime)) {
        return $datetime;
    }
    if (is_string($datetime)) {
        try {
            $datetime = new \DateTime($datetime);
        } catch (Exception $ex) {
            
        }
    }
    if (!$datetime instanceof DateTime) {
        return $datetime;
    }
    return $datetime->format($format);
}

##

function mail_get_status($email, DateTime $dt) {
    $path = Config::get('mail.status_path');
    $status = array();
    if (file_exists($path)) {
        $status = unserialize(file_get_contents($path));
    }
    if (!isset($status[$email]['y'][$dt->format('Y')])) {
        $status[$email]['y'] = array($dt->format('Y') => 0);
    }
    if (!isset($status[$email]['m'][$dt->format('Ym')])) {
        $status[$email]['m'] = array($dt->format('Ym') => 0);
    }
    if (!isset($status[$email]['d'][$dt->format('Ymd')])) {
        $status[$email]['d'] = array($dt->format('Ymd') => 0);
    }
    return $status[$email];
}

function mail_set_status($email, $data) {
    $path = Config::get('mail.status_path');
    $status = array();
    if (file_exists($path)) {
        $status = unserialize(file_get_contents($path));
    }
    $status[$email] = $data;
    file_put_contents($path, serialize($status));
}

function mail_get_limit_remain($email, DateTime $dt, $limit_date_type = null, $limit_count = null) {
    if (is_null($limit_date_type)) {
        $limit_date_type = Config::get('mail.limit_date_type');
    }
    if (is_null($limit_count)) {
        $limit_count = Config::get('mail.limit_count');
    }

    $status = mail_get_status($email, $dt);
    if ($limit_count == 0) {
        return -1;
    } else {
        if ($limit_date_type == 'day') {
            $current = $status['d'][$dt->format('Ymd')];
        } else if ($limit_date_type == 'month') {
            $current = $status['m'][$dt->format('Ym')];
        } else if ($limit_date_type == 'year') {
            $current = $status['y'][$dt->format('Y')];
        } else {
            return -1;
        }
        return $current < $limit_count ? $limit_count - $current : 0;
    }
}

function mail_write_log($msg) {
    $path = Config::get('mail.log_path');
    $msg = '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n";
    file_put_contents($path, $msg, FILE_APPEND);
}
