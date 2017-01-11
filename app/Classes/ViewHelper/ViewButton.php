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

    private function _btn_addv2($param) {
        $html = '<button type="button" class="btn btn-default btnAdd btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('add')->getUrl(['id' => $param['id']])) . '"'
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

    private function _btn_rate($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('rate')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('rate')->getPath()) . '">' . e(trans('page.btn.rate')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('rate')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }
    private function _btn_vacation($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('vacation')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('vacation')->getPath()) . '">' . e(trans('page.btn.vacation')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('vacation')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_complete($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('complete')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('complete')->getPath()) . '">' . e(trans('page.btn.complete')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('complete')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_dcomplete($param) {
        $html = '<button type="button" class="btn btn-default dcomplete" '
                . 'data-url="' . e(Sitemap::node()->getChildren('dcomplete')->getUrl()) . '" '
                . 'data-mbTitle="' . e(trans('message.question.dcomplete')) . '" data-id="'.$param['id'].'" data-start="'.$param['use_dt_start'].'" data-end="'.$param['use_dt_end'].'">' . e(trans('page.btn.dcomplete')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('dcomplete')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_notattend($param) {
        $html = '<button type="button" class="btn btn-default notattend" '
                . 'data-url="' . e(Sitemap::node()->getChildren('notattend')->getUrl()) . '" '
                . 'data-mbTitle="' . e(trans('message.question.notattend')) . '" data-id="'.$param['id'].'">' . e(trans('page.btn.notattend')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('notattend')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_removewait($param) {
        $html = '<button type="button" class="btn btn-default removewait" '
                . 'data-url="' . e(Sitemap::node()->getChildren('removewait')->getUrl()) . '" '
                . 'data-mbTitle="' . e(trans('message.question.removewait')) . '" data-id="'.$param['id'].'">' . e(trans('page.btn.removewait')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('removewait')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_confirm_pay($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('confirm_pay')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('confirm_pay')->getPath()) . '">' . e(trans('page.btn.confirm_pay')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('confirm_pay')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_complete_pay($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('complete_pay')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('complete_pay')->getPath()) . '">' . e(trans('page.btn.complete_pay')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('complete_pay')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_reminder_pay($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('reminder_pay')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('reminder_pay')->getPath()) . '">' . e(trans('page.btn.reminder_pay')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('reminder_pay')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_active($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('active')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('active')->getPath()) . '">' . e(trans('page.btn.active')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('active')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_notice($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('notice')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('notice')->getPath()) . '">' . e(trans('page.btn.notice_message')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('notice')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_activitylog($param) {
        $html = '<button type="button" class="btn btn-default btnDetail btnLink btnSetUrlBack" '
                . 'data-url="' . e(Sitemap::node()->getChildren('activitylog')->getUrl(['id' => $param['id']])) . '"'
                . 'data-routename="' . str_replace('.', '_', Sitemap::node()->getChildren('activitylog')->getPath()) . '">' . e(trans('page.btn.activitylog')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('activitylog')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }

    private function _btn_reminderadd($param) {
        $html = '<button type="button" class="btn btn-default btnDelete btnCheckShow" '
                . 'data-url="' . e(Sitemap::node()->getChildren('reminderadd')->getUrl()) . '" '
                . 'data-mbTitle="' . e(trans('message.question.delete')) . '">' . e(trans('page.btn.delete')) . '</button>';
        
        if($param['isCheckPermission'] === false || User::isAccess(Sitemap::node()->getChildren('reminderadd')->getPermissionNode()->getPath())){
            return $html;
        } else{
            return '';
        }
    }



}
