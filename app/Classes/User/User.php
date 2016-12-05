<?php

namespace App\Classes\User;

use Session;
use Cookie;
use Cache;
use Config;
use Log;

class User {

    private $userGroup = null;

    public function __construct($group = 'member') {
        $this->group($group);
    }

    public function group($group = null) {
        if (is_null($group)) {
            return $this->userGroup;
        } else {
            if (is_null(Config::get('login.group.' . $group))) {
                $errmsg = 'User group "' . $group . '" not found.';
                Log::error($errmsg);
                throw new \Exception($errmsg);
            }
            $this->userGroup = $group;
            return $this;
        }
    }

    public function isSuperAccount($account) {
        return $account === Config::get('login.group.' . $this->userGroup . '.super.account');
    }

    public function isSuperPassword($password) {
        return $password === Config::get('login.group.' . $this->userGroup . '.super.password');
    }

    public function hashPassword($password) {
        return md5(Config::get('login.pwd_enc_pre') . $password . Config::get('login.pwd_enc_post'));
    }

    // Session

    public function login($data) {
        if (isset($data['id'])) {
            Session::set($this->userGroup, $data);
        }
    }

    public function logout() {
        Session::forget($this->userGroup);
    }

    public function isLogin() {
        return !is_null(Session::get($this->userGroup . '.id'));
    }

    public function isAccess($key) {
        return in_array($key, Session::get($this->userGroup . '.permission', []));
    }

    public function id() {
        return Session::get($this->userGroup . '.id');
    }

    public function get($key = '', $default = null) {
        return Session::get($this->userGroup . rtrim('.' . $key, '.'), $default);
    }

    public function set($key, $value) {
        return Session::set($this->userGroup . '.' . $key, $value);
    }

    public function has($key) {
        return Session::has($this->userGroup . '.' . $key);
    }

    public function forget($key) {
        return Session::forget($this->userGroup . '.' . $key);
    }

    public function click($group, $id) {
        $key = 'click_' . $group;
        $arrayCook = unserialize(\Cookie::get($this->userGroup . '_' . $key, serialize(array())));
        $arraySess = $this->get($key, array());
        $arrayMerge = array_unique(array_merge($arrayCook, $arraySess));
        if (!is_null($id) && !in_array($id, $arrayMerge)) {
            $arrayMerge[] = $id;
            Cookie::queue(Cookie::forever($this->userGroup . '_' . $key, serialize($arrayMerge)));
            $this->set($key, $arrayMerge);
            
            return true;
        } 
        return false;
    }

    // Cache    

    protected function cacheAllKeysName() {
        return 'user_allkeys_' . $this->userGroup . '_' . $this->id();
    }

    protected function cacheKeyName() {
        return 'user_' . $this->userGroup . '_' . $this->id();
    }

    public function cacheClear() {
        $cacheAllKeysName = $this->cacheAllKeysName();
        $userAllKeys = Cache::get($cacheAllKeysName, []);
        foreach ($userAllKeys as $k => $v) {
            Cache::forget($v);
        }
        Cache::forget($cacheAllKeysName);
    }

    public function cacheGet($key, $default = null) {
        return Cache::get($this->cacheKeyName() . '_' . $key, $default);
    }

    public function cacheSet($key, $value) {
        $cacheAllKeysName = $this->cacheAllKeysName();
        $userAllKeys = Cache::get($cacheAllKeysName, []);
        $cacheKeyName = $this->cacheKeyName() . '_' . $key;

        Cache::forever($cacheKeyName, $value);
        if (!in_array($cacheKeyName, $userAllKeys)) {
            $userAllKeys[] = $cacheKeyName;
            Cache::forever($cacheAllKeysName, $userAllKeys);
        }
    }

    public function cacheForget($key) {
        $cacheAllKeysName = $this->cacheAllKeysName();
        $userAllKeys = Cache::get($cacheAllKeysName, []);
        $cacheKeyName = $this->cacheKeyName() . '_' . $key;

        Cache::forget($cacheKeyName);
        if (($tmpKey = array_search($cacheKeyName, $userAllKeys)) !== false) {
            unset($userAllKeys[$tmpKey]);
            Cache::forever($cacheAllKeysName, $userAllKeys);
        }
    }

}
