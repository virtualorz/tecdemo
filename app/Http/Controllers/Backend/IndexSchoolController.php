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

class IndexSchoolController extends Controller {

    public function index() {
        $listResult = DB::table('index_school')
                            ->select('index_school.id','index_school.enable','school.city','school.town','school.school_name',DB::raw('DATE_FORMAT(index_school.created_at, "%Y-%m-%d") as created_at'))
                            ->leftJoin('school','index_school.school_id','=','school.id')
                            ->orderBy('index_school.data_order','asc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));

        return $this->view;
    }

    public function add() {

        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('index_school')
                            ->select('index_school.id','index_school.photo','index_school.enable','index_school.created_at','index_school.school_id','school.city','school.town','member_admin.name as created_admin_name')
                            ->leftJoin('school','index_school.school_id','=','school.id')
                            ->leftJoin('member_admin','index_school.create_admin_id','=','member_admin.id')
                            ->where('index_school.id',$id)
                            ->get();
        $schoolResult = DB::table('school')
                            ->select('id','school_name')
                            ->where('city',$dataResult[0]['city'])
                            ->where('town',$dataResult[0]['town'])
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        $this->view->with('schoolResult',$schoolResult);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('index_school')
                            ->select('index_school.id','index_school.photo','index_school.enable','index_school.created_at','index_school.school_id','school.city','school.town','school.school_name','member_admin.name as created_admin_name')
                            ->leftJoin('school','index_school.school_id','=','school.id')
                            ->leftJoin('member_admin','index_school.create_admin_id','=','member_admin.id')
                            ->where('index_school.id',$id)
                            ->get();
        $dataResult[0]['photo'] = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($dataResult[0]['photo'][0]['url']) ? $dataResult[0]['photo'][0]['url'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['photo'] = $photo;

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'school_id' => 'integer|required',
                    'enable' => 'integer|required',
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

        //檢查學校重複
        $index_school = DB::table('index_school')
                    ->leftJoin('school','index_school.school_id','=','school.id')
                    ->where('index_school.school_id',Request::input('school_id'))
                    ->get();
        if(count($index_school) !=0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array($index_school[0]['school_name']."已在清單中，請選擇其他學校");
            return $this->view;
        }
        //檢查學校顯示數量
        if(Request::input('enable') == 1)
        {
            $school_count = DB::table('index_school')
                        ->where('enable',1)
                        ->count();
            if($school_count >=9)
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.validation');
                $this->view['detail'] = array("已達顯示數量上限，無法再新增其他學校");
                return $this->view;
            }
        }

        try {
            DB::transaction(function(){
                $id = DB::table('index_school')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'school_id'=>Request::input('school_id'),
                                    'photo'=>Request::input('photo',[]),
                                    'data_order'=>0,
                                    'enable'=>Request::input('enable'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                DB::table('index_school')
                    ->where('id',$id)
                    ->update(['data_order'=>$id]);
                $result_after = DB::table('index_school')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'index_school',
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
                    'school_id' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        //檢查學校重複
        $index_school = DB::table('index_school')
                    ->leftJoin('school','index_school.school_id','=','school.id')
                    ->where('index_school.school_id',Request::input('school_id'))
                    ->where('index_school.id','!=',Request::input('id'))
                    ->get();
        if(count($index_school) !=0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array($index_school[0]['school_name']."已在清單中，請選擇其他學校");
            return $this->view;
        }
        //檢查學校顯示數量
        if(Request::input('enable') == 1)
        {
            $school_count = DB::table('index_school')
                        ->where('enable',1)
                        ->where('id',"!=",Request::input('id'))
                        ->count();
            if($school_count >=9)
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.validation');
                $this->view['detail'] = array("已達顯示數量上限，無法再啟用其他學校");
                return $this->view;
            }
        }


        try {
            DB::transaction(function(){
                $result_before = DB::table('index_school')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('index_school')
                    ->where('id',Request::input('id'))
                    ->update(['school_id'=>Request::input('school_id'),
                                'photo'=>Request::input('photo',[]),
                                'enable'=>Request::input('enable'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('index_school')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'index_school',
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

                $result_before = DB::table('index_school')
                                    ->where('id',$id)
                                    ->get();
                DB::table('index_school')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'index_school',
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

    public function ajax_get_school() {

        $dataResult = DB::table('school')
                        ->select('id','school_name')
                        ->where('city',Request::input('city'))
                        ->where('town',Request::input('town'))
                        ->get();
        
        return $dataResult;
    }

    public function ajax_load_order() {
        
        try {
            $listResult = DB::table('index_school')
                            ->select('index_school.id','school.school_name')
                            ->leftJoin('school','index_school.school_id','=','school.id')
                            ->orderBy('index_school.data_order','asc')
                            ->get();
            

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.add');
        $this->view['data'] = $listResult;
        return $this->view;
    }

    public function ajax_set_order() {
        $validator = Validator::make(Request::all(), [
                    'sort_result' => 'string|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $sort_result = json_decode('["'.str_replace(',','","',Request::input('sort_result')).'"]',true);

        try {
            $count = 1;
            foreach ($sort_result as $k => $v) {
                if(intval($v) !=0)
                {
                    DB::table('index_school')
                        ->where('id',$v)
                        ->update(['data_order'=>$count]);
                }
                $count ++;
            }
        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }


        $this->view['msg'] = trans('message.success.edit');
        return $this->view;
    }
}
