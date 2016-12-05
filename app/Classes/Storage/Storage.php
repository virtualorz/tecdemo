<?php

namespace App\Classes\Storage;

use cURL;
use Cache;
use Carbon\Carbon;
use Log;

class Storage {

    public function auth()
    {
        $request = cURL::newRequest('get',env('SOFTLAYER_AUTH_URL'));
        $request->setHeader('X-Auth-User',env('SOFTLAYER_USER'));
        $request->setHeader('X-Auth-Key',env('SOFTLAYER_KEY'));
        $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response = $request->send();

        $expiresAt = Carbon::now()->addMinutes(120);
        Cache::put('X-Auth-Token', $response->getHeader('X-Auth-Token') , $expiresAt);
        Cache::put('X-Storage-Url', $response->getHeader('X-Storage-Url') , $expiresAt);

        return $response;
    }
    
    public function upload($file_path,$dest_path)
    {
        if(!Cache::has('X-Auth-Token'))
        {
            $this->auth();
        }
        $file = file_get_contents($file_path);
        $request = cURL::newRawRequest('put',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.rawurlencode($dest_path),$file);
        $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));
        //$request->setHeader('X-Object-Meta-name','123.jpg');
        //$request->setHeader('Content-Type','image/jpg');
        $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response = $request->send();
        if($response->info['http_code'] == '403')
        {
            $this->auth();
            $file = file_get_contents($file_path);
            $request = cURL::newRawRequest('put',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.rawurlencode($dest_path),$file);
            $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));
            //$request->setHeader('X-Object-Meta-name','123.jpg');
            //$request->setHeader('Content-Type','image/jpg');
            $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response = $request->send();
        }
        return $response;
    }

    public function move($org_path,$dest_path)
    {
        if(!Cache::has('X-Auth-Token'))
        {
            $this->auth();
        }
        $request = cURL::newRawRequest('put',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.rawurlencode($dest_path));
        $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));
        $request->setHeader('X-Copy-From',env('SOFTLAYER_CONTAINER').'/'.rawurlencode($org_path));
        $request->setHeader('Content-Length','0');
        $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response = $request->send();
        if($response->info['http_code'] == '403')
        {
            $this->auth();
            $request = cURL::newRawRequest('put',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.rawurlencode($dest_path));
            $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));
            $request->setHeader('X-Copy-From',env('SOFTLAYER_CONTAINER').'/'.rawurlencode($org_path));
            $request->setHeader('Content-Length','0');
            $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response = $request->send();
        }
        $this->delete($org_path);

        return $response;
    }

    public function delete($file_path)
    {
        if(!Cache::has('X-Auth-Token'))
        {
            $this->auth();
        }
        $request = cURL::newRawRequest('delete',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.rawurlencode($file_path));
        $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));
        $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response = $request->send();
        if($response->info['http_code'] == '403')
        {
            $this->auth();
            $request = cURL::newRawRequest('delete',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.rawurlencode($file_path));
            $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));
            $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response = $request->send();
        }
        return $response;
    }

    public function file_exists($file_path)
    {
        if(!Cache::has('X-Auth-Token'))
        {
            $this->auth();
        }
        $request = cURL::newRawRequest('get',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.rawurlencode($file_path));
        $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));log::error(Cache::get('X-Auth-Token'));
        $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response = $request->send();
        if($response->info['http_code'] == '200')
        {
            return true;
        }

        return false;

    }

    public function get_file($file_path)
    {
        if(!Cache::has('X-Auth-Token'))
        {
            $this->auth();
        }
        $request = cURL::newRawRequest('get',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.'?marker='.$file_path);
        $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));
        $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response = $request->send();
        if($response->info['http_code'] == '403')
        {
            $this->auth();
            $request = cURL::newRawRequest('get',Cache::get('X-Storage-Url').'/'.env('SOFTLAYER_CONTAINER').'/'.'?marker='.$file_path);
            $request->setHeader('X-Auth-Token',Cache::get('X-Auth-Token'));
            $request->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response = $request->send();
        }
        $files = explode("\n",$response->body);
        
        return $files;
    }

}
