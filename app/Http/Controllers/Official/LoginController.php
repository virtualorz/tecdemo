<?php

namespace App\Http\Controllers\Official;

//
use User;
use DB;
use DBOperator;
use DBProcedure;
use Request;
use Route;
use Config;
use FileUpload;
use Log;
use Sitemap;
use SitemapAccess;

class LoginController extends Controller {

    public function index() {
        
        return $this->view;
    }

    ##

    public function ajax_login() {
        
        try {
            $account = Request::input('account');
            $password = User::hashPassword(Request::input('password'));

            $dataResult = DB::table('member_data')
                            ->select('id','email','name','title','start_dt','limit_month','enable','pi_list_id')
                            ->where('email',$account)
                            ->where('password',$password)
                            ->first();
            if(count($dataResult) !=0)
            {
                if($dataResult['enable'] == 0)
                {
                    $this->view['result'] = 'no';
                    $this->view['msg'] = trans('message.error.login');
                    $this->view['detail'][] = trans('message.info.unable');

                    return $this->view;
                }
                if(strtotime($dataResult['start_dt']) > strtotime(date('Y-m-d')))
                {
                    $this->view['result'] = 'no';
                    $this->view['msg'] = trans('message.error.login');
                    $this->view['detail'][] = trans('message.info.outlimit');

                    return $this->view;
                }
                if(strtotime("+".$dataResult['limit_month']." month",strtotime($dataResult['start_dt'])) < strtotime(date('Y-m-d')))
                {
                    $this->view['result'] = 'no';
                    $this->view['msg'] = trans('message.error.login');
                    $this->view['detail'][] = trans('message.info.outlimit');

                    return $this->view;
                }

                //取得儀器使用權限
                $instrumentPermission = array();
                $instrumentResult = DB::table('member_permission')
                            ->select('permission')
                            ->where('member_data_id',$dataResult['id'])
                            ->get();
                foreach($instrumentResult as $k=>$v)
                {
                    array_push($instrumentPermission,$v['permission']);
                }

                $dataUser = [
                    'id' => $dataResult['id'],
                    'account' => $dataResult['email'],
                    'name' => $dataResult['name'],
                    'title' => $dataResult['title'],
                    'pi_list_id' => $dataResult['pi_list_id'],
                    'permission' => Sitemap::getPermissionAll('official', SitemapAccess::SUPER_REQUIRED),
                    'instrumentPermission' => $instrumentPermission,
                ];

                User::login($dataUser);
                User::cacheClear();

                $this->view['msg'] = trans('message.success.login');
                return $this->view;
            }
            else
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.login');
                $this->view['detail'][] = trans('message.question.login');

                return $this->view;
            }
        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }
    }

    public function logout() {
        $dtNow = new \DateTime();
        $this->writeDatabaseLog([
            'created_at' => $dtNow->format('Y/m/d H:i:s'),
            'operator' => DBOperator::OP_LOGOUT,
        ]);
        User::logout();

        
        return redirect('/');
    }

}
