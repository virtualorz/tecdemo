<?php

namespace App\Classes\FileUpload;

use Request;
use Config;
use File;
use Image;
use Storage;
use Log;

class FileUpload {

    public function isValidCategory($category) {
        return in_array(strtolower($category), Config::get('fileupload.dir', []));
    }

    public function isValidFileExt($filename, $validFileExt) {
        $validExt = Config::get('fileupload.ext', '');
        $tmpArrAll = explode('|', $validExt);
        $tmpArr = explode('|', strtolower($validFileExt));
        $extArr = array();
        foreach ($tmpArr as $k => $v) {
            $tmpV = trim($v);
            if (in_array($tmpV, $tmpArrAll)) {
                $extArr[] = $tmpV;
            }
        }
        if (count($extArr) > 0) {
            $validExt = implode('|', $extArr);
        }

        if (preg_match('/^(.*)\.(' . $validExt . ')$/i', $filename)) {
            return true;
        } else {
            return false;
        }
    }

    public function isValidFileSize($size, $maxSize) {
        return (intval($size) <= intval($maxSize));
    }

    public function isValidFilePx($fileInfo, $minPx){
        if($minPx != "0")
        {
            $sourcePath = $this->getRootDir() . $fileInfo['dir'] . '/' . $fileInfo['id'] . '.' . $fileInfo['ext'];
            $img = Image::make($sourcePath);
            $minWidth = explode("_",$minPx)[0];
            $minHeight = explode("_",$minPx)[1];

            if($img->width() < $minWidth || $img->height() < $minHeight)
            {
                return false;
            }
        }
        return true;
    }

    public function getRootUrl() {
        if(env('SOFTLAYER_UPLOAD',false) === true)
        {
            return env('SOFTLAYER_URL_CND') . env('SOFTLAYER_CONTAINER').'/';
        }
        else
        {
            return rtrim(url(Config::get('fileupload.root_url')), '/') . '/';
        }
    }

    public function getRootUrlABS() {

        return Config::get('url.official') . 'files/';
        
    }

    public function getRootDir() {
        return rtrim(public_path(Config::get('fileupload.root_dir')), '/') . '/';
    }

    public function saveImageResize($fileInfo) {
        $sourcePath = $this->getRootDir() . $fileInfo['dir'] . '/' . $fileInfo['id'] . '.' . $fileInfo['ext'];

        // resize gif will destroy animate
        if ($fileInfo['ext'] == 'gif') {
            foreach ($fileInfo['scale'] as $k => $v) {
                $wh = explode('_', $v);
                if ($wh[0] <= 0 && $wh[1] <= 0) {
                    continue;
                }
                $savePath = $this->getRootDir() . $fileInfo['dir'] . '/' . $fileInfo['id'] . '_' . $v . '.' . $fileInfo['ext'];

                File::copy($sourcePath, $savePath);
            }
            return;
        }


        $img = Image::make($sourcePath);
        $img->backup();
        foreach ($fileInfo['scale'] as $k => $v) {
            $wh = explode('_', $v);
            $savePath = $this->getRootDir() . $fileInfo['dir'] . '/' . $fileInfo['id'] . '_' . $v . '.' . $fileInfo['ext'];

            if ($wh[0] <= 0) {
                if ($wh[1] <= 0) {
                    continue;
                } else {
                    $img->heighten($wh[1], function($constraint) {
                        $constraint->upsize();
                    });
                }
            } else {
                if ($wh[1] <= 0) {
                    $img->widen($wh[0], function($constraint) {
                        $constraint->upsize();
                    });
                } else {
                    $img->fit($wh[0], $wh[1], function($constraint) {
                        $constraint->upsize();
                    });
                }
            }

            $img->save($savePath, 75);
            $img->reset();
        }

        $img->destroy();
    }

    public function saveImageThumb($fileInfo) {
        $sourcePath = $this->getRootDir() . $fileInfo['dir'] . '/' . $fileInfo['id'] . '.' . $fileInfo['ext'];
        $savePath = $this->getRootDir() . $fileInfo['dir'] . '/' . $fileInfo['id'] . '_thumb.' . $fileInfo['ext'];

        // resize gif will destroy animate
        if ($fileInfo['ext'] == 'gif') {
            File::copy($sourcePath, $savePath);
            return;
        }

        $img = Image::make($sourcePath);
        $img->fit(Config::get('fileupload.thumb.width'), Config::get('fileupload.thumb.height'));
        $img->save($savePath, 75);
        $img->destroy();
    }

    public function getFiles($json) {
        $files = json_decode($json, true);
        $fileList = array();
        if (is_array($files)) {
            foreach ($files as $k => $file) {
                $fileData = $file;
                $fileData['name'] = $file['name'];
                $fileData['url'] = $this->getRootUrl() . $file['dir'] . '/' . $file['id'] . '.' . $file['ext'];
                $fileData['urlThumb'] = $this->getRootUrl() . $file['dir'] . '/' . $file['id'] . '_thumb.' . $file['ext'];
                $scale = $file['scale'];
                if (!is_array($scale)) {
                    $scale = array_filter(explode(',', $scale));
                }
                if (is_array($scale)) {
                    foreach ($scale as $kk => $vv) {
                        $fileData['urlScale' . $kk] = $this->getRootUrl() . $file['dir'] . '/' . $file['id'] . '_' . $vv . '.' . $file['ext'];
                    }
                }
                $fileList[] = $fileData;
            }
        }

        return $fileList;
    }

    public function download() {
        
    }

    public function moveFile($move_obj)
    {
        $move_obj = json_decode($move_obj,true);
        if(env('SOFTLAYER_UPLOAD',false) === true)
        {
            foreach($move_obj as $k=>$v)
            {
                if(strpos($v['dir'], 'tmp/') !== false)
                {
                    $v['dir_real'] = str_replace('tmp/','',$v['dir']);
                    $files = scandir(public_path().'/files/'.$v['dir'].'/');
                    foreach($files as $file)
                    {
                        if($file != "." && $file != ".." && (strpos($file,$v['id'].".") !== false || strpos($file,$v['id']."_") !== false ))
                        {
                            $real_path = public_path().'/files/'.$v['dir'].'/'.$file;
                            $new_path = $v['dir_real'].'/'.$file;
                            if(file_exists($real_path))
                            {
                                Storage::upload($real_path,$new_path);
                            }
                        }
                    }
                    $move_obj[$k]['dir'] = $v['dir_real'];
                }
            }
        }
        else
        {
            foreach($move_obj as $k=>$v)
            {
                if(strpos($v['dir'], 'tmp/') !== false)
                {
                    $v['dir_real'] = str_replace('tmp/','',$v['dir']);
                    $v['dir_real'] = str_replace('tmp/','',$v['dir']);
                    $files = scandir(public_path().'/files/'.$v['dir'].'/');
                    foreach($files as $file)
                    {
                        if($file != "." && $file != ".." && (strpos($file,$v['id'].".") !== false || strpos($file,$v['id']."_") !== false ))
                        {
                            $real_path = public_path().'/files/'.$v['dir'].'/'.$file;
                            $new_path = public_path().'/files/'.$v['dir_real'].'/'.$file;
                            if(!file_exists(public_path().'/files/'.$v2['file']['dir_real'].'/'))
                            {
                                mkdir(public_path().'/files/'.$v2['file']['dir_real'].'/',0777);
                            }
                            if(file_exists($real_path))
                            {
                                rename($real_path,$new_path);
                            }
                        }
                    }
                    $move_obj[$k]['dir'] = $v['dir_real'];
                }
            }
        }

        return json_encode($move_obj);
    }

    public function moveEditor($move_obj) {
        $move_obj = json_decode($move_obj,true);
        if(env('SOFTLAYER_UPLOAD',false) === true)
        {
            foreach($move_obj as $k=>$v)
            {
                foreach($v['cell'] as $k1=>$v1)
                {
                    foreach($v1['item'] as $k2=>$v2)
                    {
                        if($v2['type'] == "pic")
                        {
                            if(strpos($v2['file']['dir'], 'tmp/') !== false)
                            {
                                $v2['file']['dir_real'] = str_replace('tmp/','',$v2['file']['dir']);
                                $files = scandir(public_path().'/files/'.$v2['file']['dir'].'/');
                                foreach($files as $file)
                                {
                                    if($file != "." && $file != ".." && (strpos($file,$v2['file']['id'].".") !== false || strpos($file,$v2['file']['id']."_") !== false ))
                                    {
                                        $real_path = public_path().'/files/'.$v2['file']['dir'].'/'.$file;
                                        $new_path = $v2['file']['dir_real'].'/'.$file;
                                        if(file_exists($real_path))
                                        {
                                            Storage::upload($real_path,$new_path);
                                        }
                                    }
                                }
                                $move_obj[$k]['cell'][$k1]['item'][$k2]['file']['dir'] = $v2['file']['dir_real'];
                            }
                        }
                    }
                }
            }
        }
        else
        {
            foreach($move_obj as $k=>$v)
            {
                foreach($v['cell'] as $k1=>$v1)
                {
                    foreach($v1['item'] as $k2=>$v2)
                    {
                        if($v2['type'] == "pic")
                        {
                            if(strpos($v2['file']['dir'], 'tmp/') !== false)
                            {
                                $v2['file']['dir_real'] = str_replace('tmp/','',$v2['file']['dir']);
                                $files = scandir(public_path().'/files/'.$v2['file']['dir'].'/');
                                foreach($files as $file)
                                {
                                    if($file != "." && $file != ".." && (strpos($file,$v2['file']['id'].".") !== false || strpos($file,$v2['file']['id']."_") !== false ))
                                    {
                                        $real_path = public_path().'/files/'.$v2['file']['dir'].'/'.$file;
                                        $new_path = public_path().'/files/'.$v2['file']['dir_real'].'/'.$file;
                                        if(!file_exists(public_path().'/files/'.$v2['file']['dir_real'].'/'))
                                        {
                                            mkdir(public_path().'/files/'.$v2['file']['dir_real'].'/',0777);
                                        }
                                        if(file_exists($real_path))
                                        {
                                            rename($real_path,$new_path);
                                        }
                                    }
                                }
                                $move_obj[$k]['cell'][$k1]['item'][$k2]['file']['dir'] = $v2['file']['dir_real'];
                            }
                        }
                    }
                }
            }
        }

        return json_encode($move_obj);
    }

    public function deleteFile($org_obj,$new_obj = null) {
        $org_obj = json_decode($org_obj,true);
        if($new_obj != null)
        {//比對刪除舊檔
            $new_obj = json_decode($new_obj,true);
            if(env('SOFTLAYER_UPLOAD',false) === true)
            {
                foreach($org_obj as $k=>$v)
                {
                    $to_del = true;
                    foreach($new_obj as $k1=>$v1)
                    {
                        if($v['id'] == $v1['id'])
                        {
                            $to_del  =false;
                        }
                    }

                    if($to_del)
                    {
                        $files = Storage::get_file($v['dir']);
                        foreach($files as $file)
                        {
                            if($file != "." && $file != ".." && (strpos($file,$v['id'].".") !== false || strpos($file,$v['id']."_") !== false ))
                            {
                                $real_path = $file;
                                if(Storage::file_exists($real_path))
                                {
                                    Storage::delete($real_path);
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                foreach($org_obj as $k=>$v)
                {
                    $to_del = true;
                    foreach($new_obj as $k1=>$v1)
                    {
                        if($v['id'] == $v1['id'])
                        {
                            $to_del  =false;
                        }
                    }

                    if($to_del)
                    {
                        $files = scandir(public_path().'/files/'.$v['dir'].'/');
                        foreach($files as $file)
                        {
                            if($file != "." && $file != ".." && (strpos($file,$v['id'].".") !== false || strpos($file,$v['id']."_") !== false ))
                            {
                                $real_path = public_path().'/files/'.$v['dir'].'/'.$file;
                                $new_path = public_path().'/files/'.$v['dir_real'].'/'.$file;
                                if(file_exists($real_path))
                                {
                                    unlink($real_path);
                                }
                            }
                        }
                    }
                }
            }
        }
        else
        {//全數刪除
            if(env('SOFTLAYER_UPLOAD',false) === true)
            {
                foreach($org_obj as $k=>$v)
                {
                    $files = Storage::get_file($v['dir']);
                    foreach($files as $file)
                    {
                        if($file != "." && $file != ".." && (strpos($file,$v['id'].".") !== false || strpos($file,$v['id']."_") !== false ))
                        {
                            $real_path = $file;
                            if(Storage::file_exists($real_path))
                            {
                                Storage::delete($real_path);
                            }
                        }
                    }
                }
            }
            else
            {
                foreach($org_obj as $k=>$v)
                {
                    $files = scandir(public_path().'/files/'.$v['dir'].'/');
                    foreach($files as $file)
                    {
                        if($file != "." && $file != ".." && (strpos($file,$v['id'].".") !== false || strpos($file,$v['id']."_") !== false ))
                        {
                            $real_path = public_path().'/files/'.$v['dir'].'/'.$file;
                            $new_path = public_path().'/files/'.$v['dir_real'].'/'.$file;
                            if(file_exists($real_path))
                            {
                                unlink($real_path);
                            }
                        }
                    }
                }
            }

        }

        return 0;
    }

    public function deleteEditor($org_obj,$new_obj = null) {
        $org_obj = json_decode($org_obj,true);
        if($new_obj != null)
        {//比對刪除舊檔
            $new_obj = json_decode($new_obj,true);
            if(env('SOFTLAYER_UPLOAD',false) === true)
            {
                foreach($org_obj as $k=>$v)
                {
                    foreach($v['cell'] as $k1=>$v1)
                    {
                        foreach($v1['item'] as $k2=>$v2)
                        {
                            if($v2['type'] == "pic")
                            {
                                $to_del = true;
                                foreach($new_obj as $k3=>$v3)
                                {
                                    foreach($v3['cell'] as $k4=>$v4)
                                    {
                                        foreach($v4['item'] as $k5=>$v5)
                                        {
                                            if($v5['type'] == "pic")
                                            {
                                                if($v2['file']['id'] == $v5['file']['id'])
                                                {
                                                    $to_del  =false;
                                                }
                                            }
                                        }
                                    }
                                }
                                if($to_del)
                                {
                                    $files = Storage::get_file($v2['file']['dir']);
                                    foreach($files as $file)
                                    {
                                        if($file != "." && $file != ".." && (strpos($file,$v2['file']['id'].".") !== false || strpos($file,$v2['file']['id']."_") !== false ))
                                        {
                                            $real_path = $file;
                                            if(Storage::file_exists($real_path))
                                            {
                                                Storage::delete($real_path);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                foreach($org_obj as $k=>$v)
                {
                    foreach($v['cell'] as $k1=>$v1)
                    {
                        foreach($v1['item'] as $k2=>$v2)
                        {
                            if($v2['type'] == "pic")
                            {
                                $to_del = true;
                                foreach($new_obj as $k3=>$v3)
                                {
                                    foreach($v3['cell'] as $k4=>$v4)
                                    {
                                        foreach($v4['item'] as $k5=>$v5)
                                        {
                                            if($v5['type'] == "pic")
                                            {
                                                if($v2['file']['id'] == $v5['file']['id'])
                                                {
                                                    $to_del  =false;
                                                }
                                            }
                                        }
                                    }
                                }
                                if($to_del)
                                {
                                    $files = scandir(public_path().'/files/'.$v2['file']['dir'].'/');
                                    foreach($files as $file)
                                    {
                                        if($file != "." && $file != ".." && (strpos($file,$v2['file']['id'].".") !== false || strpos($file,$v2['file']['id']."_") !== false ))
                                        {
                                            $real_path = public_path().'/files/'.$v2['file']['dir'].'/'.$file;
                                            if(file_exists($real_path))
                                            {
                                                unlink($real_path);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        else
        {//全數刪除
            if(env('SOFTLAYER_UPLOAD',false) === true)
            {
                foreach($org_obj as $k=>$v)
                {
                    foreach($v['cell'] as $k1=>$v1)
                    {
                        foreach($v1['item'] as $k2=>$v2)
                        {
                            if($v2['type'] == "pic")
                            {
                                $files = Storage::get_file($v2['file']['dir']);
                                foreach($files as $file)
                                {
                                    if($file != "." && $file != ".." && (strpos($file,$v2['file']['id'].".") !== false || strpos($file,$v2['file']['id']."_") !== false ))
                                    {
                                        $real_path = $file;
                                        if(Storage::file_exists($real_path))
                                        {
                                            Storage::delete($real_path);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                foreach($org_obj as $k=>$v)
                {
                    foreach($v['cell'] as $k1=>$v1)
                    {
                        foreach($v1['item'] as $k2=>$v2)
                        {
                            if($v2['type'] == "pic")
                            {
                                $files = scandir(public_path().'/files/'.$v2['file']['dir'].'/');
                                foreach($files as $file)
                                {
                                    if($file != "." && $file != ".." && (strpos($file,$v2['file']['id'].".") !== false || strpos($file,$v2['file']['id']."_") !== false ))
                                    {
                                        $real_path = public_path().'/files/'.$v2['file']['dir'].'/'.$file;
                                        if(file_exists($real_path))
                                        {
                                            unlink($real_path);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

        }

        return 0;
    }

}
