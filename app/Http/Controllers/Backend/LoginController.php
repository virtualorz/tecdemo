<?php

namespace App\Http\Controllers\Backend;

//
use User;
use DB;
use DBOperator;
use Validator;
use Request;
use Config;
use Sitemap;
use SitemapAccess;

class LoginController extends Controller {

    public function index() {

        return $this->view;
    }

    public function ajax_login() {
        $validator = Validator::make(Request::all(), [
                    'admin-account' => 'string|required|max:50',
                    'admin-password' => 'string|required|max:50',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $dtNow = new \DateTime();
        $account = Request::input('admin-account', '');
        $password = Request::input('admin-password', '');
        $hashPassword = User::hashPassword($password);

        // get data
        $dataResult = [];
        if (User::isSuperAccount($account)) {
            $dataResult = Config::get('login.group.' . User::group() . '.super');
            $dataResult['enable'] = 1;
        } else {
            $dataResult = DB::table("member_admin")
                    ->select([
                        "member_admin.id",
                        "member_admin.email as account",
                        "member_admin.password",
                        "member_admin.name",
                        "member_admin_permission.content as permission",
                        "member_admin.enable",
                    ])
                    ->leftJoin('member_admin_permission','member_admin.permission_id','=','member_admin_permission.id')
                    ->where("member_admin.email", $account)
                    ->first();
        }


        if (count($dataResult) <= 0) { // nodata
            $this->writeDatabaseLog([
                'created_at' => $dtNow->format('Y/m/d H:i:s'),
                'operator' => DBOperator::OP_LOGIN_FAIL,
                'table' => 'member_admin',
                'data_after' => [
                    'account' => $account,
                ],
            ]);

            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.login');
            $this->view['detail'][] = trans('message.question.login');

            return $this->view;
        } else if (strcmp($hashPassword, $dataResult['password']) != 0) { //pwd incorrect
            $this->writeDatabaseLog([
                'created_at' => $dtNow->format('Y/m/d H:i:s'),
                'operator' => DBOperator::OP_LOGIN_FAIL_PASSWORD,
                'table' => 'member_admin',
                'data_id' => $dataResult['id'],
                'data_after' => [
                    'account' => $account,
                ],
            ]);

            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.login');
            $this->view['detail'][] = trans('message.question.login');

            return $this->view;
        } else if ($dataResult['enable'] != 1) { //account not enable
            $this->writeDatabaseLog([
                'created_at' => $dtNow->format('Y/m/d H:i:s'),
                'operator' => DBOperator::OP_LOGIN_FAIL_ENABLE,
                'table' => 'member_admin',
                'data_id' => $dataResult['id'],
                'data_after' => [
                    'account' => $account,
                ],
            ]);

            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.login');
            $this->view['detail'][] = trans('message.info.login_enable');

            return $this->view;
        }

        // success
        $dataUser = [];
        if (User::isSuperAccount($account)) {
            $dataUser = [
                'id' => $dataResult['id'],
                'account' => $dataResult['account'],
                'name' => $dataResult['name'],
                'title' => '--',
                'permission' => Sitemap::getPermissionAll('backend', SitemapAccess::SUPER_REQUIRED),
            ];
        } else {
            
            $dataUser = [
                'id' => $dataResult['id'],
                'account' => $dataResult['account'],
                'name' => $dataResult['name'],
                'title' => '--',
                'permission' => json_decode($dataResult['permission']),
            ];
        }
        $this->writeDatabaseLog([
            'created_at' => $dtNow->format('Y/m/d H:i:s'),
            'operator' => DBOperator::OP_LOGIN_SUCCESS,
            'table' => 'admin',
            'data_id' => $dataUser['id'],
            'data_after' => [
                'account' => $account,
            ],
        ]);
        User::login($dataUser);
        User::cacheClear();

        $this->view['msg'] = trans('message.success.login');
        return $this->view;
    }

    public function ajax_logout() {
        $dtNow = new \DateTime();
        $this->writeDatabaseLog([
            'created_at' => $dtNow->format('Y/m/d H:i:s'),
            'operator' => DBOperator::OP_LOGOUT,
        ]);
        User::logout();

        $this->view['msg'] = trans('message.success.logout');
        return $this->view;
    }

}
