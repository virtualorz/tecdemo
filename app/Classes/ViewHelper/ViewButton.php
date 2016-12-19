<?php

namespace App\Classes\ViewHelper;

use Sitemap;
use User;
use Route;

trait ViewButton {
    
    public function getButton($name, $param = []){
        $buttonName = strtolower(trim($name));
        $method = '_btn_' . $buttonName;
        if (!method_exists($this, $method)) {
            return '';
        }
        
        $defaultParam = [
            'id' => 0,
            'isCheckPermission' => true
        ];
        $parameters = array_merge($defaultParam, $param);

        return $this->$method($parameters);
    }
    
    ##

    private function _btn_add($param) {
        $html = '<button type="button" class="btn btn-default btnAdd btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('add')->getUrl()) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('add')->getPath()) . '">' . e(trans('page.btn.add')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('add')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }
    
    private function _btn_edit($param) {
        $html = '<button type="button" class="btn btn-default btnEdit btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('edit')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('edit')->getPath()) . '">' . e(trans('page.btn.edit')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('edit')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_delete($param) {
        $html = '<button type="button" class="btn btn-default btnDelete btnCheckShow" '
                . 'data-url="' . e(Sitemap::node()->getChildren('delete')->getUrl()) . '" '
                . 'data-mbTitle="' . e(trans('message.question.delete')) . '">' . e(trans('page.btn.delete')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('delete')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_enable($param) {
        $html = '<button type="button" class="btn btn-default btnEnable btnCheckShow" '
                . 'data-url="' . e(Sitemap::node()->getChildren('enable')->getUrl()) . '" '
                . 'data-mbTitle="' . e(trans('message.question.enable')) . '">' . e(trans('page.btn.enable')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('enable')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_disable($param) {
        $html = '<button type="button" class="btn btn-default btnDisable btnCheckShow" '
                . 'data-url="' . e(Sitemap::node()->getChildren('enable')->getUrl()) . '" '
                . 'data-mbTitle="' . e(trans('message.question.disable')) . '">' . e(trans('page.btn.disable')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('enable')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_order($param) {
        $html = '<button type="button" class="btn btn-default btnOrder" '
                . 'data-url="' . e(Sitemap::node()->getChildren('order')->getUrl()) . '" '
                . 'data-page="' . e(Route::input('optional.page', 1)) . '" '
                . 'data-mbTitle="' . e(trans('message.question.delete')) . '">' . e(trans('page.btn.order')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('order')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_orderv2($param) {
        $html = '<button type="button" class="btn btn-default '.$param['class'].'" '
                . 'data-url="' . e(Sitemap::node()->getChildren('load_order')->getUrl()) . '" '
                . '>' . e(trans('page.btn.order')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('load_order')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_detail($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('detail')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('detail')->getPath()) . '">' . e(trans('page.btn.detail')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('detail')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_reservation($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('reservation')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('reservation')->getPath()) . '">' . e(trans('page.btn.reservation')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('reservation')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_attend($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('attend')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('attend')->getPath()) . '">' . e(trans('page.btn.attend')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('attend')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_attendv2($param) {
        $html = '<button type="button" class="btn btn-default btnAttend" '
                . 'data-url="' . e(Sitemap::node()->getChildren('attend')->getUrl()) . '" '
                . 'data-id="' . $param['id'] . '">' . e(trans('page.btn.attend')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('attend')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_pass($param) {
        $html = '<button type="button" class="btn btn-default btnPass" '
                . 'data-url="' . e(Sitemap::node()->getChildren('pass')->getUrl()) . '" '
                . 'data-id="' . $param['id'] . '">' . e(trans('page.btn.pass')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('pass')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_list($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('list')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('list')->getPath()) . '">' . e(trans('page.btn.student_list')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('list')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_attend_cancel($param) {
        $html = '<button type="button" class="btn btn-default btnAttend" '
                . 'data-url="' . e(Sitemap::node()->getChildren('attend_cancel')->getUrl()) . '" '
                . 'data-id="' . $param['id'] . '">' . e(trans('page.btn.attend_cancel')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('attend_cancel')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_pass_cancel($param) {
        $html = '<button type="button" class="btn btn-default btnPass" '
                . 'data-url="' . e(Sitemap::node()->getChildren('pass_cancel')->getUrl()) . '" '
                . 'data-id="' . $param['id'] . '">' . e(trans('page.btn.pass_cancel')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('pass_cancel')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_submit($param) {
        $html = '<button type="submit" class="btn btn-default btnSubmit">' . e(trans('page.btn.submit')) . '</button>';
        
        return $html;
    }
    
    private function _btn_cancel($param) {
        $html = '<button type="button" class="btn btn-default btnCancel btnLink">' . e(trans('page.btn.cancel')) . '</button>';
        
        return $html;
    }

    private function _btn_cancelv2($param) {
        $html = '<button type="button" class="btn btn-default btnDelete btnCheckShow" '
                . 'data-url="' . e(Sitemap::node()->getChildren('cancel')->getUrl()) . '" '
                . 'data-mbTitle="' . e(trans('message.question.cancel')) . '">' . e(trans('page.btn.cancel')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('cancel')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }
    
    private function _btn_back($param) {
        $html = '<button type="button" class="btn btn-default btnBack btnLink">' . e(trans('page.btn.back')) . '</button>';
        
        return $html;
    }

    private function _btn_search($param) {
        $html = '<button type="submit" name="submit_search" class="btn btn-default btnSubmit" value="1">' . e(trans('page.btn.search')) . '</button>';
        
        return $html;
    }
    
    private function _btn_reset($param) {
        $html = '<button type="button" class="btn btn-default btnReset">' . e(trans('page.btn.reset')) . '</button>';
        
        return $html;
    }

}
