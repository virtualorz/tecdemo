<?php

namespace App\Classes\FileUpload;

use Request;
use Config;
use File;
use Image;

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
        return rtrim(url(Config::get('fileupload.root_url')), '/') . '/';
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

}
