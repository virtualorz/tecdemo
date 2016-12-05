<?php

namespace App\Http\Controllers\Backend;

//
use DB;
use DBOperator;
use DBProcedure;
use User;
use Request;
use Route;
use Validator;
use Config;
use Sitemap;
use SitemapAccess;
use Log;
use FileUpload;
use Mail;

class SchoolListController extends Controller {

    public function index() {
        $location = Request::input('location', '');
        $city = Request::input('city', '');
        $town = Request::input('town', '');
        $school_id = Request::input('school_id', '');

        $listResult = DB::table('school');
        if($location != "")
        {
            $listResult->where('school.location','=',$location);
        }
        if($city != "")
        {
            $listResult->where('school.city','=',$city);
        }
        if($town != "")
        {
            $listResult->where('school.town','=',$town);
        }
        if($school_id != "")
        {
            $listResult->where('school.id','=',$school_id);
        }

        $listResult = $listResult->select('id','location','city','town','school_name',DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_at'))
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $schoolResult = array();
        if($city != '' && $town != '')
        {
            $schoolResult = DB::table('school')
                            ->select('id','school_name')
                            ->where('city',$city)
                            ->where('town',$town)
                            ->get();
        }

        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('location',Config::get('data.location'));
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        $this->view->with('schoolResult',$schoolResult);
        return $this->view;
    }

    public function add() {

        $this->view->with('location',Config::get('data.location'));
        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('school')
                            ->select('school.id','school.created_at','school.location','school.city','school.town','school.account','school.password','school.school_name','school.photo','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','school.create_admin_id','=','member_admin.id')
                            ->where('school.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('location',Config::get('data.location'));
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('school')
                            ->select('school.id','school.created_at','school.location','school.city','school.town','school.account','school.password','school.school_name','school.photo','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','school.create_admin_id','=','member_admin.id')
                            ->where('school.id',$id)
                            ->get();
        $dataResult[0]['photo'] = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($dataResult[0]['photo'][0]['url']) ? $dataResult[0]['photo'][0]['url'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['photo'] = $photo;

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('location',Config::get('data.location'));
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'location' => 'integer|required',
                    'city' => 'integer|required',
                    'town' => 'integer|required',
                    'account' => 'string|required|max:20',
                    'password' => 'string|required',
                    'school_name' => 'string|required|max:100',
        ]);
        if ($validator->fails()) {
            $invalid[] = $validator->errors();
        }
        if (count($invalid) > 0) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $invalid;
            return $this->view;
        }

        //檢查帳號重複
        $school = DB::table('school')
                    ->where('account',Request::input('account'))
                    ->get();
        if(count($school) !=0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('帳號重複');
            return $this->view;
        }

        try {
            DB::transaction(function(){
                $id = DB::table('school')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'location'=>Request::input('location'),
                                    'city'=>Request::input('city'),
                                    'town'=>Request::input('town'),
                                    'account'=>Request::input('account'),
                                    'password'=>User::hashPassword(Request::input('password')),
                                    'school_name'=>Request::input('school_name'),
                                    'photo'=>Request::input('photo'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('school')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'school',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.add');
        return $this->view;
    }

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'location' => 'integer|required',
                    'city' => 'integer|required',
                    'town' => 'integer|required',
                    'account' => 'string|required|max:20',
                    'password' => 'string|required',
                    'school_name' => 'string|required|max:100',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        //檢查帳號重複
        $school = DB::table('school')
                    ->where('account',Request::input('account'))
                    ->where('id','!=',Request::input('id'))
                    ->get();
        if(count($school) !=0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('帳號重複');
            return $this->view;
        }


        try {
            DB::transaction(function(){
                $result_before = DB::table('school')
                                    ->where('id',Request::input('id'))
                                    ->get();
                $password = Request::input('password');
                if($result_before[0]['password'] != Request::input('password'))
                {
                    $password = User::hashPassword(Request::input('password'));
                }
                DB::table('school')
                    ->where('id',Request::input('id'))
                    ->update(['location'=>Request::input('location'),
                                'city'=>Request::input('city'),
                                'town'=>Request::input('town'),
                                'account'=>Request::input('account'),
                                'password'=>$password,
                                'school_name'=>Request::input('school_name'),
                                'photo'=>Request::input('photo'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('school')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'school',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
            });

        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        }

        $this->view['msg'] = trans('message.success.edit');
        return $this->view;
    }

    public function ajax_delete() {
        $validator = Validator::make(Request::all(), [
                    'id' => 'array|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $ids = Request::input('id', []);
        try {
            foreach ($ids as $k => $v) {
                $id = $v;

                //刪除學校資料
                $result_before = DB::table('school')
                                    ->where('id',$id)
                                    ->get();
                DB::table('school')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'school',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                
                //刪除首頁學校資料
                $result_before = DB::table('index_school')
                                    ->where('school_id',$id)
                                    ->get();
                DB::table('index_school')
                    ->where('school_id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'index_school',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                //刪除訪視輔導資料
                $result_before = DB::table('school_tutor')
                                    ->where('school_id',$id)
                                    ->get();
                DB::table('school_tutor')
                    ->where('school_id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'school_tutor',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                //刪除研習活動資料
                $result_before = DB::table('learning')
                                    ->where('school_id',$id)
                                    ->get();
                DB::table('learning')
                    ->where('school_id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'learning',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                //刪除學校最新消息
                $result_before = DB::table('news')
                                    ->where('school_id',$id)
                                    ->get();
                DB::table('news')
                    ->where('school_id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'news',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                //刪除學校執行情形
                $result_before = DB::table('school_execute')
                                    ->where('school_id',$id)
                                    ->get();
                DB::table('school_execute')
                    ->where('school_id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'school_execute',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                //刪除學校計畫介紹
                $result_before = DB::table('school_plan')
                                    ->where('school_id',$id)
                                    ->get();
                DB::table('school_plan')
                    ->where('school_id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'school_plan',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
            }
        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        } catch (\Exception $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = $ex->getMessage();
            return $this->view;
        }

        
        $this->view['msg'] = trans('message.success.delete');
        return $this->view;
    }

    public function ajax_get_town() {

        $twTown = Config::get('data.twTown');
        return $twTown[Request::input('id')];
    }
}
